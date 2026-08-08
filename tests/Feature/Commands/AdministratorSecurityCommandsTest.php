<?php

namespace Tests\Feature\Commands;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdministratorSecurityCommandsTest extends TestCase
{
    use RefreshDatabase;

    public function test_fresh_migrations_do_not_create_an_administrator(): void
    {
        $this->assertDatabaseCount('users', 0);
    }

    public function test_administrator_provisioning_requires_explicit_strong_credentials(): void
    {
        $this->artisan('admin:provision', [
            '--first-name' => 'Secure',
            '--last-name' => 'Operator',
            '--email' => 'operator@example.test',
            '--mobile' => '09121111111',
            '--password' => 'weak-password',
        ])->assertFailed();

        $this->assertDatabaseCount('users', 0);

        $this->artisan('admin:provision', [
            '--first-name' => 'Secure',
            '--last-name' => 'Operator',
            '--email' => 'operator@example.test',
            '--mobile' => '09121111111',
            '--password' => 'Long-Secure-Password-42!',
        ])->assertSuccessful();

        $user = User::where('email', 'operator@example.test')->firstOrFail();
        $this->assertTrue($user->is_admin);
        $this->assertSame('admin', $user->role);
        $this->assertTrue($user->active);
        $this->assertNotNull($user->email_verified_at);
        $this->assertTrue(Hash::check('Long-Secure-Password-42!', $user->password));
    }

    public function test_provisioning_never_overwrites_an_existing_user(): void
    {
        $existing = User::factory()->create(['email' => 'existing@example.test']);

        $this->artisan('admin:provision', [
            '--first-name' => 'Secure',
            '--last-name' => 'Operator',
            '--email' => $existing->email,
            '--mobile' => '09121111111',
            '--password' => 'Long-Secure-Password-42!',
        ])->assertFailed();

        $existing->refresh();
        $this->assertFalse($existing->is_admin);
        $this->assertNotSame('admin', $existing->role);
    }

    public function test_bootstrap_check_is_read_only_and_can_confirm_a_password_candidate(): void
    {
        $user = User::factory()->create([
            'email' => 'admin@test.com',
            'mobile' => '09120000000',
            'password' => Hash::make('historical-candidate'),
        ]);
        $original = $user->getRawOriginal();

        $this->artisan('admin:check-bootstrap', ['--password' => 'historical-candidate'])
            ->expectsOutputToContain('account detected')
            ->expectsOutputToContain('MATCH')
            ->assertFailed();

        $this->assertSame($original, $user->fresh()->getRawOriginal());
    }

    public function test_bootstrap_check_succeeds_when_the_account_is_absent(): void
    {
        $this->artisan('admin:check-bootstrap')
            ->expectsOutputToContain('No historical bootstrap')
            ->assertSuccessful();
    }
}
