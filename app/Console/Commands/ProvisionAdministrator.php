<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;

final class ProvisionAdministrator extends Command
{
    protected $signature = 'admin:provision
        {--first-name= : Administrator first name}
        {--last-name= : Administrator last name}
        {--email= : Unique administrator email address}
        {--mobile= : Unique administrator mobile number}
        {--password= : New administrator password}';

    protected $description = 'Explicitly provision a new administrator with strong credentials';

    public function handle(): int
    {
        $input = [
            'first_name' => $this->option('first-name'),
            'last_name' => $this->option('last-name'),
            'email' => $this->option('email'),
            'mobile' => $this->option('mobile'),
            'password' => $this->option('password'),
        ];

        $validator = Validator::make($input, [
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'mobile' => ['required', 'string', 'max:255', 'unique:users,mobile'],
            'password' => ['required', 'string', Password::min(12)->mixedCase()->letters()->numbers()->symbols()],
        ]);

        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                $this->error($error);
            }

            return self::FAILURE;
        }

        $attributes = $validator->validated();
        $attributes['password'] = Hash::make($attributes['password']);
        $attributes['role'] = 'admin';
        $attributes['is_admin'] = true;
        $attributes['active'] = true;
        $attributes['email_verified_at'] = now();

        $administrator = new User($attributes);
        $administrator->email_verified_at = $attributes['email_verified_at'];
        $administrator->save();

        $this->info('Administrator provisioned successfully.');

        return self::SUCCESS;
    }
}
