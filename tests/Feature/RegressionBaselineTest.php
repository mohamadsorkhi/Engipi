<?php

namespace Tests\Feature;

use Illuminate\Routing\Route;
use Tests\TestCase;

class RegressionBaselineTest extends TestCase
{
    /**
     * @dataProvider criticalRouteProvider
     */
    public function test_critical_named_routes_keep_their_http_contract(
        string $name,
        string $uri,
        string $method,
        array $middleware
    ): void {
        $route = app('router')->getRoutes()->getByName($name);

        $this->assertInstanceOf(Route::class, $route, "The [$name] route is missing.");
        $this->assertSame($uri, $route->uri());
        $this->assertContains($method, $route->methods());

        foreach ($middleware as $expectedMiddleware) {
            $this->assertContains($expectedMiddleware, $route->gatherMiddleware());
        }
    }

    public static function criticalRouteProvider(): array
    {
        return [
            'public landing page' => ['root', '/', 'GET', ['web']],
            'guest project form' => ['guest.project', 'post-project', 'GET', ['web', 'guest']],
            'public project page' => ['projects.show', 'projects/{project}', 'GET', ['web']],
            'profile selection' => ['profile.select', 'profile/select', 'GET', ['web', 'auth']],
            'user dashboard' => ['user.dashboard', 'user/dashboard', 'GET', ['web', 'auth', 'active_role']],
            'project file download' => ['user.project-files.download', 'user/project-files/{projectFile}/download', 'GET', ['web', 'auth']],
            'employer project creation' => ['employer.projects.create', 'employer/projects/create', 'GET', ['web', 'auth', 'active_role:employer']],
            'specialist matched projects' => ['user.matched-projects.index', 'user/matched-projects', 'GET', ['web', 'auth', 'active_role:specialist']],
            'admin dashboard' => ['admin.dashboard', 'admin/dashboard', 'GET', ['web', 'auth', 'admin']],
        ];
    }

    /**
     * @dataProvider protectedPageProvider
     */
    public function test_guests_are_redirected_from_protected_pages(string $routeName): void
    {
        $this->get(route($routeName))->assertRedirect(route('login'));
    }

    public static function protectedPageProvider(): array
    {
        return [
            'profile selection' => ['profile.select'],
            'user dashboard' => ['user.dashboard'],
            'employer project creation' => ['employer.projects.create'],
            'specialist matched projects' => ['user.matched-projects.index'],
            'admin dashboard' => ['admin.dashboard'],
        ];
    }

    public function test_guest_project_form_remains_available_to_guests(): void
    {
        $this->get(route('guest.project'))->assertOk();
    }
}
