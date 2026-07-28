<?php

namespace App\Services\ProjectDocuments;

use App\Contracts\ProjectDocumentInspector;
use App\ValueObjects\ProjectDocumentInspectionResult;
use Illuminate\Http\UploadedFile;

final class ClamAvProjectDocumentInspector implements ProjectDocumentInspector
{
    public function __construct(
        private readonly string $host,
        private readonly int $port,
        private readonly float $connectTimeout,
        private readonly int $readTimeout,
        private readonly int $chunkBytes,
        private readonly int $maximumBytes,
    ) {}

    public function inspect(UploadedFile $file): ProjectDocumentInspectionResult
    {
        if (! $file->isValid() || $file->getSize() > $this->maximumBytes) {
            return ProjectDocumentInspectionResult::inspectionFailed();
        }

        $socket = $this->connect();

        if (! is_resource($socket)) {
            return ProjectDocumentInspectionResult::scannerUnavailable();
        }

        $stream = fopen($file->getRealPath(), 'rb');

        if (! is_resource($stream)) {
            fclose($socket);

            return ProjectDocumentInspectionResult::inspectionFailed();
        }

        try {
            stream_set_timeout($socket, $this->readTimeout);

            if (! $this->writeAll($socket, "zINSTREAM\0")) {
                return ProjectDocumentInspectionResult::inspectionFailed();
            }

            while (! feof($stream)) {
                $chunk = fread($stream, $this->chunkBytes);

                if ($chunk === false) {
                    return ProjectDocumentInspectionResult::inspectionFailed();
                }

                if ($chunk !== '' && ! $this->writeAll($socket, pack('N', strlen($chunk)).$chunk)) {
                    return ProjectDocumentInspectionResult::inspectionFailed();
                }
            }

            if (! $this->writeAll($socket, pack('N', 0))) {
                return ProjectDocumentInspectionResult::inspectionFailed();
            }

            $response = stream_get_contents($socket);
            $metadata = stream_get_meta_data($socket);

            if ($response === false || ($metadata['timed_out'] ?? false)) {
                return ProjectDocumentInspectionResult::scannerUnavailable();
            }

            $response = rtrim($response, "\0\r\n");

            if (str_ends_with($response, ' OK')) {
                return ProjectDocumentInspectionResult::clean();
            }

            if (str_ends_with($response, ' FOUND')) {
                return ProjectDocumentInspectionResult::malwareDetected();
            }

            return ProjectDocumentInspectionResult::inspectionFailed();
        } finally {
            fclose($stream);
            fclose($socket);
        }
    }

    /**
     * @return resource|false
     */
    private function connect()
    {
        $errorCode = 0;
        $errorMessage = '';

        return @stream_socket_client(
            sprintf('tcp://%s:%d', $this->host, $this->port),
            $errorCode,
            $errorMessage,
            $this->connectTimeout,
            STREAM_CLIENT_CONNECT
        );
    }

    /**
     * @param  resource  $socket
     */
    private function writeAll($socket, string $payload): bool
    {
        $written = 0;
        $length = strlen($payload);

        while ($written < $length) {
            $bytes = fwrite($socket, substr($payload, $written));

            if ($bytes === false || $bytes === 0) {
                return false;
            }

            $written += $bytes;
        }

        return true;
    }
}
