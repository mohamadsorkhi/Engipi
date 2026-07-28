<?php

namespace Tests\Feature\Uploads;

use App\Contracts\ProjectDocumentInspector;
use App\Models\Process;
use App\Models\SkillDomain;
use App\Models\User;
use App\Models\UserProfile;
use App\ValueObjects\ProjectDocumentInspectionResult;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use RuntimeException;
use Tests\Fakes\FakeProjectDocumentInspector;
use Tests\TestCase;

class ProjectDocumentInspectionTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('public');
        Storage::fake('local');
    }

    public function test_clean_upload_is_inspected_before_existing_private_storage_flow(): void
    {
        $inspector = $this->bindInspector(ProjectDocumentInspectionResult::clean());

        $this->postProjectWithFiles([
            UploadedFile::fake()->create('brief.pdf', 10, 'application/pdf'),
        ])->assertOk()
            ->assertJsonPath('status', 'success');

        $this->assertSame(1, $inspector->inspectionCount);
        $this->assertSame($inspector, $this->app->make(ProjectDocumentInspector::class));
        $this->assertDatabaseCount('projects', 1);
        $this->assertDatabaseHas('project_files', ['storage_disk' => 'local']);
        $this->assertSame([], Storage::disk('public')->allFiles());
        $this->assertCount(1, Storage::disk('local')->allFiles());
    }

    public function test_infected_upload_fails_closed_without_persistence_or_sensitive_output(): void
    {
        $this->bindInspector(ProjectDocumentInspectionResult::malwareDetected());

        $response = $this->postProjectWithFiles([
            UploadedFile::fake()->create('customer-secret.pdf', 10, 'application/pdf'),
        ]);

        $this->assertRejectedWithoutPersistence($response);
        $response->assertJsonMissing(['customer-secret.pdf'])
            ->assertJsonMissing(['Eicar-Test-Signature']);
    }

    public function test_scanner_unavailable_fails_closed_without_persistence(): void
    {
        $this->bindInspector(ProjectDocumentInspectionResult::scannerUnavailable());

        $response = $this->postProjectWithFiles([
            UploadedFile::fake()->create('brief.pdf', 10, 'application/pdf'),
        ]);

        $this->assertRejectedWithoutPersistence($response);
    }

    public function test_inspection_failure_result_fails_closed_without_persistence(): void
    {
        $this->bindInspector(ProjectDocumentInspectionResult::inspectionFailed());

        $response = $this->postProjectWithFiles([
            UploadedFile::fake()->create('brief.pdf', 10, 'application/pdf'),
        ]);

        $this->assertRejectedWithoutPersistence($response);
    }

    public function test_inspection_exception_is_redacted_and_fails_closed(): void
    {
        $this->bindInspector(new RuntimeException(
            'scanner output: Eicar-Test-Signature at C:\\private\\customer-secret.pdf hash=secret-hash'
        ));

        $response = $this->postProjectWithFiles([
            UploadedFile::fake()->create('customer-secret.pdf', 10, 'application/pdf'),
        ]);

        $this->assertRejectedWithoutPersistence($response);
        $body = $response->getContent();
        $this->assertStringNotContainsString('customer-secret.pdf', $body);
        $this->assertStringNotContainsString('Eicar-Test-Signature', $body);
        $this->assertStringNotContainsString('secret-hash', $body);
        $this->assertStringNotContainsString('C:\\private', $body);
    }

    public function test_multiple_uploads_with_one_infected_file_create_nothing(): void
    {
        $inspector = $this->bindInspector(
            ProjectDocumentInspectionResult::clean(),
            ProjectDocumentInspectionResult::malwareDetected(),
        );

        $response = $this->postProjectWithFiles([
            UploadedFile::fake()->create('clean.pdf', 10, 'application/pdf'),
            UploadedFile::fake()->create('infected.pdf', 10, 'application/pdf'),
        ]);

        $this->assertRejectedWithoutPersistence($response, 'files.1');
        $this->assertSame(2, $inspector->inspectionCount);
    }

    public function test_no_file_project_does_not_invoke_inspector(): void
    {
        $inspector = $this->bindInspector(ProjectDocumentInspectionResult::malwareDetected());

        $this->postProjectWithFiles([])->assertOk();

        $this->assertSame(0, $inspector->inspectionCount);
        $this->assertDatabaseCount('projects', 1);
        $this->assertDatabaseCount('project_files', 0);
    }

    private function bindInspector(
        ProjectDocumentInspectionResult|RuntimeException ...$outcomes
    ): FakeProjectDocumentInspector {
        $inspector = new FakeProjectDocumentInspector(...$outcomes);
        $this->app->instance(ProjectDocumentInspector::class, $inspector);

        return $inspector;
    }

    private function postProjectWithFiles(array $files)
    {
        $employer = User::factory()->create();
        UserProfile::query()->create([
            'user_id' => $employer->id,
            'type' => 'employer',
        ]);
        $domain = SkillDomain::query()->create([
            'name' => 'Inspection domain '.Str::random(8),
        ]);
        $process = Process::query()->create([
            'skill_domain_id' => $domain->id,
            'name' => 'Inspection process '.Str::random(8),
        ]);

        return $this->actingAs($employer)
            ->withSession(['active_role' => 'employer'])
            ->postJson(route('user.projects.store'), [
                'title' => 'Inspected project',
                'description' => 'Synthetic project used only by isolated inspection tests.',
                'work_type' => 'remote',
                'domains' => [$domain->id],
                'processes' => [[
                    'id' => $process->id,
                    'level' => 'practical',
                ]],
                'files' => $files,
            ]);
    }

    private function assertRejectedWithoutPersistence($response, string $field = 'files.0'): void
    {
        $response->assertUnprocessable()
            ->assertJsonValidationErrors($field);
        $this->assertDatabaseCount('projects', 0);
        $this->assertDatabaseCount('project_files', 0);
        $this->assertSame([], Storage::disk('public')->allFiles());
        $this->assertSame([], Storage::disk('local')->allFiles());
    }
}
