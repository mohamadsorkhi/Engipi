<?php

namespace App\ValueObjects;

enum ProjectDocumentInspectionStatus: string
{
    case Clean = 'clean';
    case MalwareDetected = 'malware_detected';
    case ScannerUnavailable = 'scanner_unavailable';
    case InspectionFailed = 'inspection_failed';
}
