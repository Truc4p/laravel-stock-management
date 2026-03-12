<?php

// Bootstrap SQLite database on Vercel cold start (ephemeral /tmp, resets each cold start)
if (!file_exists('/tmp/database.sqlite')) {
    touch('/tmp/database.sqlite');
    $root = realpath(__DIR__ . '/..');
    $artisan = escapeshellarg("$root/artisan");
    exec(PHP_BINARY . " $artisan migrate --force 2>&1");
    exec(PHP_BINARY . " $artisan db:seed --force 2>&1");
}

require __DIR__ . '/../public/index.php';
