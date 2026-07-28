<?php

namespace Tests\Feature\Uploads;

use App\Contracts\ProjectDocumentInspector;
use App\Models\Process;
use App\Models\SkillDomain;
use App\Models\User;
use App\Models\UserProfile;
use App\Rules\AllowedProjectDocument;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\Fakes\FakeProjectDocumentInspector;
use Tests\TestCase;

class ProjectUploadValidationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->app->instance(ProjectDocumentInspector::class, new FakeProjectDocumentInspector);
    }

    #[DataProvider('allowedFiles')]
    public function test_approved_extension_and_mime_pairs_are_allowed(string $name, string $mimeType): void
    {
        $validator = $this->validateFile(UploadedFile::fake()->create($name, 10, $mimeType));

        $this->assertTrue($validator->passes(), $validator->errors()->first('file'));
    }

    public static function allowedFiles(): array
    {
        return [
            'PDF' => ['brief.pdf', 'application/pdf'],
            'plain text' => ['notes.txt', 'text/plain'],
            'CSV' => ['data.csv', 'text/csv'],
            'legacy Word' => ['requirements.doc', 'application/msword'],
            'Word Open XML' => ['requirements.docx', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'],
            'legacy Excel' => ['budget.xls', 'application/vnd.ms-excel'],
            'Excel Open XML' => ['budget.xlsx', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'],
            'ZIP' => ['drawings.zip', 'application/zip'],
        ];
    }

    #[DataProvider('blockedFiles')]
    public function test_active_executable_macro_and_mismatched_files_are_blocked(string $name, string $mimeType): void
    {
        $validator = $this->validateFile(UploadedFile::fake()->create($name, 10, $mimeType));

        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('file', $validator->errors()->toArray());
    }

    public static function blockedFiles(): array
    {
        return [
            'HTML' => ['page.html', 'text/html'],
            'SVG' => ['diagram.svg', 'image/svg+xml'],
            'JavaScript' => ['script.js', 'text/javascript'],
            'PHP' => ['script.php', 'application/x-httpd-php'],
            'Windows executable' => ['tool.exe', 'application/x-dosexec'],
            'shell script' => ['run.sh', 'application/x-sh'],
            'macro Word' => ['requirements.docm', 'application/vnd.ms-word.document.macroenabled.12'],
            'macro Excel' => ['budget.xlsm', 'application/vnd.ms-excel.sheet.macroenabled.12'],
            'renamed executable' => ['tool.pdf', 'application/x-dosexec'],
            'allowed MIME with executable extension' => ['tool.exe', 'application/pdf'],
        ];
    }

    public function test_file_larger_than_ten_megabytes_is_blocked(): void
    {
        $validator = $this->validateFile(
            UploadedFile::fake()->create('brief.pdf', 10241, 'application/pdf')
        );

        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('file', $validator->errors()->toArray());
    }

    public function test_mixed_valid_and_invalid_uploads_create_no_project_file_or_stored_file(): void
    {
        Storage::fake('public');
        [$employer, $domain, $process] = $this->createEmployerProjectContext();

        $this->actingAs($employer)
            ->withSession(['active_role' => 'employer'])
            ->postJson(route('user.projects.store'), $this->validProjectPayload($domain, $process, [
                UploadedFile::fake()->create('brief.pdf', 10, 'application/pdf'),
                UploadedFile::fake()->create('payload.html', 10, 'text/html'),
            ]))
            ->assertUnprocessable()
            ->assertJsonValidationErrors('files.1');

        $this->assertDatabaseCount('projects', 0);
        $this->assertDatabaseCount('project_files', 0);
        $this->assertSame([], Storage::disk('public')->allFiles());
    }

    public function test_approved_upload_creates_a_private_project_file(): void
    {
        Storage::fake('public');
        Storage::fake('local');
        [$employer, $domain, $process] = $this->createEmployerProjectContext();

        $this->actingAs($employer)
            ->withSession(['active_role' => 'employer'])
            ->postJson(route('user.projects.store'), $this->validProjectPayload($domain, $process, [
                UploadedFile::fake()->create('brief.pdf', 10, 'application/pdf'),
            ]))
            ->assertOk()
            ->assertJsonPath('status', 'success');

        $this->assertDatabaseCount('projects', 1);
        $this->assertDatabaseCount('project_files', 1);
        $this->assertDatabaseHas('project_files', ['storage_disk' => 'local']);
        $this->assertSame([], Storage::disk('public')->allFiles());
        $this->assertCount(1, Storage::disk('local')->allFiles());
    }

    public function test_project_creation_without_files_remains_unchanged(): void
    {
        Storage::fake('public');
        [$employer, $domain, $process] = $this->createEmployerProjectContext();

        $this->actingAs($employer)
            ->withSession(['active_role' => 'employer'])
            ->postJson(route('user.projects.store'), $this->validProjectPayload($domain, $process))
            ->assertOk()
            ->assertJsonPath('status', 'success');

        $this->assertDatabaseCount('projects', 1);
        $this->assertDatabaseCount('project_files', 0);
        $this->assertSame([], Storage::disk('public')->allFiles());
    }

    private function validateFile(UploadedFile $file)
    {
        return Validator::make(
            ['file' => $file],
            ['file' => ['file', 'max:10240', new AllowedProjectDocument]]
        );
    }

    private function createEmployerProjectContext(): array
    {
        $employer = User::factory()->create();
        UserProfile::query()->create([
            'user_id' => $employer->id,
            'type' => 'employer',
        ]);

        $domain = SkillDomain::query()->create([
            'name' => 'Upload test domain '.Str::random(8),
        ]);

        $process = Process::query()->create([
            'skill_domain_id' => $domain->id,
            'name' => 'Upload test process '.Str::random(8),
        ]);

        return [$employer, $domain, $process];
    }

    private function validProjectPayload(SkillDomain $domain, Process $process, array $files = []): array
    {
        return [
            'title' => 'Upload validation project',
            'description' => 'Synthetic project used only by isolated feature tests.',
            'work_type' => 'remote',
            'domains' => [$domain->id],
            'processes' => [[
                'id' => $process->id,
                'level' => 'practical',
            ]],
            'files' => $files,
        ];
    }
}
