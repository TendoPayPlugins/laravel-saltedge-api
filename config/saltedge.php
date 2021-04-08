<?php

return [
    'url' => env('SALTEDGE_URL'),
    'app_id' => env('SALTEDGE_APP_ID'),
    'secret' => env('SALTEDGE_SECRET'),
    'private_key_path' => env('SALTEDGE_PRIVATE_KEY_PATH'),
    'storage_mode' => env('SALTEDGE_STORAGE_MODE', 's3'),
];
