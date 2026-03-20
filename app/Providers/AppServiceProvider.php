<?php

namespace App\Providers;

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Fix: disableForeignKeyConstraints() was called globally — wrong.
        //
        // This was only needed for PlanetScale (Vitess engine) which doesn't
        // support FK constraints at all. On local MySQL and standard servers,
        // disabling FK constraints globally means bad data silently enters
        // the database with no errors — very dangerous.
        //
        // Rule:
        //   Local MySQL / standard MySQL → FK constraints ON (default) ✅
        //   PlanetScale (Vitess)         → FK constraints OFF ✅
        //
        // We detect the DB driver and only disable when actually needed.

        if ($this->isPlanetScale()) {
            Schema::disableForeignKeyConstraints();
        }

        // Ensure string columns default to utf8mb4 (supports Tamil Unicode text)
        Schema::defaultStringLength(191);
    }

    /**
     * Detect if we are connected to PlanetScale (Vitess).
     * PlanetScale connection strings contain 'psdb.cloud' in the host.
     */
    private function isPlanetScale(): bool
    {
        $host = config('database.connections.mysql.host', '');
        return str_contains((string) $host, 'psdb.cloud');
    }
}
