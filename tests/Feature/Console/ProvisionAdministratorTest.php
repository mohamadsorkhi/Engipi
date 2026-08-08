<?php

namespace Tests\Feature\Console;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ProvisionAdministratorTest extends TestCase
{
    use RefreshDatabase;

    protected function tearDown(): void
    {
        putenv('ENGIPI_ADMIN_PASSWORD');

        parent::tearDown();
    }

    public function test_fresh_migrations_do_not_create_a_hard_coded_administrator(): void
    {
        $this->assertDatabaseCount('users', 0);
    }

    public function test_it_provisions_an_administrator_with_a_password_from_the_environment(): void
    {
        putenv('ENGIPI_ADMIN_PASSWORD=a-secure-admin-password');

        $this->artisan('admin:provision', [
            'email' => 'ADMIN@EXAMPLE.COM',
            '--first-name' => 'System',
            '--last-name' => 'Administrator',
        ])->assertSuccessful();

        $administrator = User::query()->where('email', 'admin@example.com')->firstOrFail();

        $this->assertTrue($administrator->is_admin);
        $this->assertSame('admin', $administrator->role);
        $this->assertTrue($administrator->active);
        $this->assertNotNull($administrator->email_verified_at);
        $this->assertTrue(Hash::check('a-secure-admin-password', $administrator->password));
    }

    public function test_it_does_not_modify_an_existing_user_without_explicit_promotion(): void
    {
        $user = User::factory()->create(['email' => 'existing@example.com']);
        $originalPassword = $user->password;

        $this->artisan('admin:provision', ['email' => $user->email])
            ->assertFailed();

        $user->refresh();

        $this->assertFalse($user->is_admin);
        $this->assertSame('worker', $user->role);
        $this->assertSame($originalPassword, $user->password);
    }

    public function test_explicit_promotion_preserves_existing_account_data(): void
    {
        $user = User::factory()->create([
            'email' => 'existing@example.com',
            'first_name' => 'Existing',
            'active' => false,
        ]);
        $originalId = $user->id;
        $originalPassword = $user->password;

        $this->artisan('admin:provision', [
            'email' => $user->email,
            '--promote-existing' => true,
        ])->assertSuccessful();

        $user->refresh();

        $this->assertTrue($user->is_admin);
        $this->assertSame('admin', $user->role);
        $this->assertSame($originalId, $user->id);
        $this->assertSame('Existing', $user->first_name);
        $this->assertSame($originalPassword, $user->password);
        $this->assertFalse($user->active);
        $this->assertDatabaseCount('users', 1);
    }
}
