<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schema;

class EnsureDatabaseMigrated
{
    public function handle(Request $request, Closure $next)
    {
        try {
            if (!Schema::hasTable('users')) {
                Artisan::call('migrate', ['--force' => true]);
                Artisan::call('db:seed', ['--force' => true]);
            }
        } catch (\Throwable $e) {
            // Log but don't crash — let the request continue
            logger()->error('Migration failed: ' . $e->getMessage());
        }

        return $next($request);
    }
}
