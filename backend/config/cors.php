<?php

return [
    'paths' => ['api/*'],
    'allowed_methods'   => ['*'],
    'allowed_origins'   => [
        env('ALLOWED_ORIGIN_1', 'http://localhost:5173'),
    ],
    'allowed_headers'   => ['*'],
    'exposed_headers'   => [],
    'max_age'           => 3600,
    'supports_credentials' => true,
];