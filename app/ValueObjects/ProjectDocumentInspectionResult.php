<?php

namespace App\ValueObjects;

final readonly class ProjectDocumentInspectionResult
{
    private function __construct(public ProjectDocumentInspectionStatus $status) {}

    public static function clean(): self
    {
        return new self(ProjectDocumentInspectionStatus::Clean);
    }

    public static function malwareDetected(): self
    {
        return new self(ProjectDocumentInspectionStatus::MalwareDetected);
    }

    public static function scannerUnavailable(): self
    {
        return new self(ProjectDocumentInspectionStatus::ScannerUnavailable);
    }

    public static function inspectionFailed(): self
    {
        return new self(ProjectDocumentInspectionStatus::InspectionFailed);
    }

    public function isClean(): bool
    {
        return $this->status === ProjectDocumentInspectionStatus::Clean;
    }
}
