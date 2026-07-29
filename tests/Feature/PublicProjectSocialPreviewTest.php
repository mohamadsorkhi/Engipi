<?php

namespace Tests\Feature;

use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicProjectSocialPreviewTest extends TestCase
{
    use RefreshDatabase;

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
}
