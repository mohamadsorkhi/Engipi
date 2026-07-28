<?php

return [
    'clamav' => [
        'host' => env('CLAMAV_HOST', '127.0.0.1'),
        'port' => (int) env('CLAMAV_PORT', 3310),
        'connect_timeout' => (float) env('CLAMAV_CONNECT_TIMEOUT', 2),
        'read_timeout' => (int) env('CLAMAV_READ_TIMEOUT', 15),
        'chunk_bytes' => 8192,
        'maximum_bytes' => 10 * 1024 * 1024,
    ],
];
