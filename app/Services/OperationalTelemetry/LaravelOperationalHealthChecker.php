<?php

namespace App\Services\OperationalTelemetry;

use App\Contracts\OperationalHealthChecker;
use App\ValueObjects\OperationalHealthResult;
use Illuminate\Cache\CacheManager;
use Illuminate\Database\DatabaseManager;
use Illuminate\Filesystem\FilesystemManager;
use Throwable;

final class LaravelOperationalHealthChecker implements OperationalHealthChecker
{
    public function __construct(
        private readonly DatabaseManager $database,
        private readonly FilesystemManager $filesystems,
        private readonly CacheManager $cache,
        private readonly string $scannerHost,
        private readonly int $scannerPort,
        private readonly float $scannerTimeout,
    ) {}

    public function check(): OperationalHealthResult
    {
        $checks = [
            'database' => fn () => $this->database->connection()->select('SELECT 1'),
            'private_storage' => fn () => $this->filesystems->disk('local')->directoryExists(''),
            'public_storage' => fn () => $this->filesystems->disk('public')->directoryExists(''),
            'scanner' => fn () => $this->scannerIsHealthy(),
            'cache' => fn () => $this->cache->store()->getStore(),
        ];
        $components = [];
        $durations = [];

        foreach ($checks as $component => $check) {
            $startedAt = microtime(true);

            try {
                $result = $check();
                $components[$component] = $result === false ? 'unhealthy' : 'healthy';
            } catch (Throwable) {
                $components[$component] = 'unhealthy';
            }

            $durations[$component] = (int) round((microtime(true) - $startedAt) * 1000);
        }

        return new OperationalHealthResult($components, $durations);
    }

    private function scannerIsHealthy(): bool
    {
        $errorCode = 0;
        $errorMessage = '';
        $socket = @stream_socket_client(
            sprintf('tcp://%s:%d', $this->scannerHost, $this->scannerPort),
            $errorCode,
            $errorMessage,
            $this->scannerTimeout,
            STREAM_CLIENT_CONNECT
        );

        if (! is_resource($socket)) {
            return false;
        }

        try {
            $seconds = max(1, (int) ceil($this->scannerTimeout));
            stream_set_timeout($socket, $seconds);

            if (fwrite($socket, "zPING\0") !== 6) {
                return false;
            }

            $response = stream_get_contents($socket);
            $metadata = stream_get_meta_data($socket);

            return ! ($metadata['timed_out'] ?? false)
                && rtrim((string) $response, "\0\r\n") === 'PONG';
        } finally {
            fclose($socket);
        }
    }
}
