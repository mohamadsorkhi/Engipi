<?php

namespace App\ValueObjects;

enum OperationalEvent: string
{
    case ProjectDocumentInspectionCompleted = 'project_document.inspection.completed';
    case ProjectFilesOperationStarted = 'project_files.operation.started';
    case ProjectFilesOperationCompleted = 'project_files.operation.completed';
    case ProjectFilesOptionsRejected = 'project_files.operation.options_rejected';
    case OperationsHealthCompleted = 'operations.health.completed';

    public function allowedContext(): array
    {
        return match ($this) {
            self::ProjectDocumentInspectionCompleted => ['event_version', 'outcome', 'duration_ms'],
            self::ProjectFilesOperationStarted => ['event_version', 'mode'],
            self::ProjectFilesOptionsRejected => ['event_version', 'reason'],
            self::ProjectFilesOperationCompleted => array_merge(
                ['event_version', 'mode', 'result', 'duration_ms'],
                self::operationCounters(),
            ),
            self::OperationsHealthCompleted => [
                'event_version', 'status',
                'database_status', 'database_duration_ms',
                'private_storage_status', 'private_storage_duration_ms',
                'public_storage_status', 'public_storage_duration_ms',
                'scanner_status', 'scanner_duration_ms',
                'cache_status', 'cache_duration_ms',
            ],
        };
    }

    public function allowedStringValues(string $key): ?array
    {
        return match ($key) {
            'outcome' => ['clean', 'malware_detected', 'scanner_unavailable', 'inspection_failed', 'exception'],
            'mode' => ['dry_run', 'execute', 'rollback', 'orphans'],
            'result' => ['success', 'unresolved', 'failed'],
            'reason' => ['invalid_batch', 'mutually_exclusive_modes'],
            'status', 'database_status', 'private_storage_status', 'public_storage_status',
            'scanner_status', 'cache_status' => ['healthy', 'degraded', 'unhealthy'],
            default => null,
        };
    }

    private static function operationCounters(): array
    {
        return [
            'total', 'batches_processed', 'public_bytes_detected', 'private_bytes_detected',
            'legacy_public_only', 'legacy_both', 'legacy_private_only', 'already_private',
            'private_with_public_copy', 'migrated', 'public_copies_removed',
            'rollback_already_public', 'rolled_back', 'orphan_files_scanned',
            'public_orphan_files', 'public_orphan_bytes', 'local_orphan_files',
            'local_orphan_bytes', 'private_metadata_public_only', 'missing_both',
            'content_mismatch', 'metadata_mismatch', 'failed', 'elapsed_milliseconds',
        ];
    }
}
