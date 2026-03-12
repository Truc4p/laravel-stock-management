<?php

// Bootstrap SQLite database on Vercel cold start
if (!file_exists('/tmp/database.sqlite')) {
    touch('/tmp/database.sqlite');

    $app = require __DIR__ . '/../bootstrap/app.php';
    $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
    $kernel->call('migrate', ['--force' => true]);
    $kernel->call('db:seed', ['--force' => true]);
}

require __DIR__ . '/../public/index.php';
