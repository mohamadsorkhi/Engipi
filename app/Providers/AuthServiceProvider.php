<?php

namespace App\Providers;

use App\Models\Message;
use App\Models\Project;
use App\Models\ProjectFile;
use App\Policies\MessagePolicy;
use App\Policies\ProjectFilePolicy;
use App\Policies\ProjectPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Message::class => MessagePolicy::class,
        Project::class => ProjectPolicy::class,
        ProjectFile::class => ProjectFilePolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        //
    }
}
