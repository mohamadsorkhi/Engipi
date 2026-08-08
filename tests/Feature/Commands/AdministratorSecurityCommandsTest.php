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
        $original = User::query()->findOrFail($user->id)->getRawOriginal();

        $this->artisan('admin:check-bootstrap', ['--password' => 'historical-candidate'])
            ->expectsOutputToContain('account detected')
            ->expectsOutputToContain('MATCH')
            ->assertFailed();

        $persisted = User::query()->findOrFail($user->id)->getRawOriginal();
        $this->assertSame($original, $persisted);
    }

    public function test_bootstrap_check_succeeds_when_the_account_is_absent(): void
    {
        $this->artisan('admin:check-bootstrap')
            ->expectsOutputToContain('No historical bootstrap')
            ->assertSuccessful();
    }

    public function test_bootstrap_check_tests_every_matching_account_regardless_of_insertion_order(): void
    {
        foreach ([['email', 'mobile'], ['mobile', 'email']] as $insertionOrder) {
            User::query()->delete();

            foreach ($insertionOrder as $identifier) {
                User::factory()->create($identifier === 'email' ? [
                    'email' => 'admin@test.com',
                    'mobile' => '09121111111',
                    'password' => Hash::make('different-password'),
                ] : [
                    'email' => 'mobile-match@example.test',
                    'mobile' => '09120000000',
                    'password' => Hash::make('historical-candidate'),
                ]);
            }

            $this->artisan('admin:check-bootstrap', ['--password' => 'historical-candidate'])
                ->expectsOutputToContain('account detected')
                ->expectsOutputToContain('MATCH')
                ->assertFailed();
        }
    }

    public function test_bootstrap_check_detects_either_historical_identifier_by_itself(): void
    {
        foreach ([
            ['email' => 'admin@test.com', 'mobile' => '09121111111'],
            ['email' => 'mobile-match@example.test', 'mobile' => '09120000000'],
        ] as $attributes) {
            User::query()->delete();
            User::factory()->create($attributes);

            $this->artisan('admin:check-bootstrap')
                ->expectsOutputToContain('account detected')
                ->assertFailed();
        }
    }
}
