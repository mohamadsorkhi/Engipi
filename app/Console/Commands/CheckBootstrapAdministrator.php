<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

final class CheckBootstrapAdministrator extends Command
{
    protected $signature = 'admin:check-bootstrap
        {--password= : Historical bootstrap password candidate to verify}';

    protected $description = 'Read-only check for the historical bootstrap administrator account';

    public function handle(): int
    {
        $user = User::query()
            ->where('email', 'admin@test.com')
            ->orWhere('mobile', '09120000000')
            ->first();

        if ($user === null) {
            $this->info('No historical bootstrap administrator account was detected.');

            return self::SUCCESS;
        }

        $this->warn('Historical bootstrap administrator account detected. Follow the security runbook.');

        if (is_string($this->option('password')) && $this->option('password') !== '') {
            $matches = Hash::check($this->option('password'), $user->password);
            $this->line('Supplied historical password candidate: '.($matches ? 'MATCH' : 'no match'));
        } else {
            $this->line('Password was not checked; supply --password to verify the historical credential.');
        }

        return self::FAILURE;
    }
}
