<?php

namespace Tests\Feature;

use App\Models\Project;
use App\Models\Skill;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Routing\Route;
use Illuminate\Support\Facades\Route as RouteFacade;
use Tests\TestCase;

class PublicProjectSocialPreviewTest extends TestCase
{
    use RefreshDatabase;

    private const DEFAULT_DESCRIPTION = 'پروژه مهندسی خود را ثبت کنید و با متخصصان واقعی در حوزه‌های مختلف مهندسی همکاری کنید.';

    public function test_public_page_routes_keep_their_complete_public_contract(): void
    {
        $publicRoutes = [
            'projects.show' => 'projects/{project}',
            'landing.v2' => 'landing-v2',
            'landing.v2.a' => 'landing-v2-a',
            'landing.v2.b' => 'landing-v2-b',
            'landing.v2.c' => 'landing-v2-c',
        ];

        foreach ($publicRoutes as $name => $uri) {
            $route = RouteFacade::getRoutes()->getByName($name);

            $this->assertInstanceOf(Route::class, $route, "The [{$name}] route is not registered.");
            $this->assertSame($uri, $route->uri(), "The [{$name}] route URI changed.");
            $this->assertSame(['GET', 'HEAD'], $route->methods(), "The [{$name}] HTTP methods changed.");

            $middlewareAliases = collect($route->gatherMiddleware())
                ->map(fn (string $middleware) => explode(':', $middleware, 2)[0]);

            foreach (['auth', 'admin', 'active_role', 'role', 'profile'] as $restrictiveMiddleware) {
                $this->assertNotContains(
                    $restrictiveMiddleware,
                    $middlewareAliases,
                    "The public [{$name}] route must not use [{$restrictiveMiddleware}] middleware."
                );
            }
        }
    }

    public function test_public_project_can_be_resolved_by_uuid(): void
    {
        $project = $this->createProject('UUID-ROUTE-2026');

        $this->get('/projects/'.$project->id)->assertOk();
    }

    public function test_public_project_can_be_resolved_by_short_id(): void
    {
        $project = $this->createProject('QUB4WR');

        $this->get('/projects/'.$project->short_id)->assertOk();
    }

    public function test_invalid_public_project_identifier_returns_not_found(): void
    {
        $this->get('/projects/DOES-NOT-EXIST')->assertNotFound();
    }

    public function test_project_preview_is_public_and_contains_dynamic_social_metadata(): void
    {
        $project = Project::create([
            'employer_id' => User::factory()->create()->id,
            'short_id' => 'OG-PREVIEW-2026',
            'title' => 'پروژه آزمایشی مهندسی',
            'description' => 'توضیحات پروژه برای پیش‌نمایش شبکه‌های اجتماعی',
            'work_type' => 'remote',
        ]);

        $response = $this->get(route('projects.show', $project));

        $response
            ->assertOk()
            ->assertSee('<meta property="og:title" content="پروژه آزمایشی مهندسی">', false)
            ->assertSee('<meta property="og:description" content="توضیحات پروژه برای پیش‌نمایش شبکه‌های اجتماعی">', false)
            ->assertSee('<meta property="og:url" content="https://www.engipi.com/projects/'.$project->getRouteKey().'">', false)
            ->assertSee('<meta property="og:image" content="https://www.engipi.com/images/engipi-og.jpg">', false)
            ->assertSee('<meta name="twitter:card" content="summary_large_image">', false)
            ->assertSee('<meta name="twitter:image" content="https://www.engipi.com/images/engipi-og.jpg">', false);
    }

    public function test_project_preview_uses_the_engipi_description_when_project_description_is_empty(): void
    {
        $project = Project::create([
            'employer_id' => User::factory()->create()->id,
            'short_id' => 'OG-FALLBACK-2026',
            'title' => 'پروژه بدون توضیح',
            'description' => '',
            'work_type' => 'remote',
        ]);

        $this->get(route('projects.show', $project))
            ->assertOk()
            ->assertSee(
                '<meta property="og:description" content="'.self::DEFAULT_DESCRIPTION.'">',
                false
            );
    }

    public function test_public_project_page_renders_available_details_and_escapes_user_content(): void
    {
        $project = Project::create([
            'employer_id' => User::factory()->create()->id,
            'short_id' => 'PUBLIC-DESIGN',
            'title' => '<script>alert("title")</script>',
            'description' => "توضیحات پروژه\n<script>alert(\"description\")</script>",
            'work_type' => 'hybrid',
            'budget_min' => 1000000,
            'budget_max' => 2500000,
            'duration_days' => 30,
            'deadline_date' => '2026-12-21',
            'view_count' => 125,
        ]);
        $skill = Skill::create(['name' => '<b>Revit</b>']);
        $project->skills()->attach($skill);

        $this->get('/projects/'.$project->short_id)
            ->assertOk()
            ->assertSee('ترکیبی')
            ->assertSee('1,000,000 تا 2,500,000 تومان')
            ->assertSee('30 روز')
            ->assertSee('125')
            ->assertSee('&lt;script&gt;alert(&quot;title&quot;)&lt;/script&gt;', false)
            ->assertSee('&lt;script&gt;alert(&quot;description&quot;)&lt;/script&gt;', false)
            ->assertSee('&lt;b&gt;Revit&lt;/b&gt;', false)
            ->assertDontSee('<script>alert("title")</script>', false)
            ->assertDontSee('<b>Revit</b>', false);
    }
    private function createProject(string $shortId): Project
    {
        return Project::create([
            'employer_id' => User::factory()->create()->id,
            'short_id' => $shortId,
            'title' => 'Public project',
            'description' => 'Public project description',
            'work_type' => 'remote',
        ]);
    }
}
