<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

final class ProvisionAdministrator extends Command
{
    protected $signature = 'admin:provision
        {email : Email address of the administrator}
        {--first-name= : First name for a new administrator}
        {--last-name= : Last name for a new administrator}
        {--password-env=ENGIPI_ADMIN_PASSWORD : Environment variable containing the new administrator password}
        {--promote-existing : Explicitly grant administrator access to an existing user}';

    protected $description = 'Safely create an administrator or explicitly promote an existing user';

    public function handle(): int
    {
        $email = mb_strtolower(trim((string) $this->argument('email')));

        if (Validator::make(['email' => $email], ['email' => ['required', 'email']])->fails()) {
            $this->error('A valid administrator email address is required.');

            return self::FAILURE;
        }

        $existingUser = User::query()->where('email', $email)->first();

        if ($existingUser !== null) {
            return $this->handleExistingUser($existingUser);
        }

        return $this->createAdministrator($email);
    }

    private function handleExistingUser(User $user): int
    {
        if ($user->is_admin && $user->role === 'admin') {
            $this->info('The user is already an administrator; no changes were made.');

            return self::SUCCESS;
        }

        if (! $this->option('promote-existing')) {
            $this->error('A user with this email already exists. Re-run with --promote-existing to grant administrator access without replacing the account.');

            return self::FAILURE;
        }

        $user->forceFill([
            'role' => 'admin',
            'is_admin' => true,
        ])->save();

        $this->info('The existing user was promoted. Their profile, password, and other account data were preserved.');

        return self::SUCCESS;
    }

    private function createAdministrator(string $email): int
    {
        $passwordEnvironmentVariable = (string) $this->option('password-env');
        $password = getenv($passwordEnvironmentVariable);

        if ($password === false) {
            $password = $this->secret('Administrator password (at least 12 characters)');
            $confirmation = $this->secret('Confirm administrator password');

            if ($password !== $confirmation) {
                $this->error('The password confirmation does not match.');

                return self::FAILURE;
            }
        }

        $attributes = [
            'first_name' => trim((string) $this->option('first-name')),
            'last_name' => trim((string) $this->option('last-name')),
            'password' => $password,
        ];
        $validator = Validator::make($attributes, [
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'password' => ['required', 'string', 'min:12'],
        ]);

        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                $this->error($error);
            }

            return self::FAILURE;
        }

        DB::transaction(function () use ($email, $attributes): void {
            $administrator = new User;
            $administrator->forceFill([
                'first_name' => $attributes['first_name'],
                'last_name' => $attributes['last_name'],
                'email' => $email,
                'password' => Hash::make($attributes['password']),
                'email_verified_at' => now(),
                'role' => 'admin',
                'is_admin' => true,
                'active' => true,
            ])->save();
        });

        $this->info('Administrator created successfully.');

        return self::SUCCESS;
    }
}
