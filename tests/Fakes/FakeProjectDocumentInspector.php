<?php

namespace Tests\Fakes;

use App\Contracts\ProjectDocumentInspector;
use App\ValueObjects\ProjectDocumentInspectionResult;
use Illuminate\Http\UploadedFile;
use Throwable;

final class FakeProjectDocumentInspector implements ProjectDocumentInspector
{
    /** @var array<int, ProjectDocumentInspectionResult|Throwable> */
    private array $outcomes;

    public int $inspectionCount = 0;

    public function __construct(ProjectDocumentInspectionResult|Throwable ...$outcomes)
    {
        $this->outcomes = $outcomes;
    }

    public function inspect(UploadedFile $file): ProjectDocumentInspectionResult
    {
        $this->inspectionCount++;
        $outcome = array_shift($this->outcomes) ?? ProjectDocumentInspectionResult::clean();

        if ($outcome instanceof Throwable) {
            throw $outcome;
        }

        return $outcome;
    }
}
