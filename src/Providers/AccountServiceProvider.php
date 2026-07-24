<?php

namespace Zerp\Account\Providers;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AccountServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $routesPath = __DIR__.'/../Routes/web.php';
        if (file_exists($routesPath)) {
            $this->loadRoutesFrom($routesPath);
        }

        $apiRoutesPath = __DIR__.'/../Routes/api.php';
        if (file_exists($apiRoutesPath)) {
            $this->loadRoutesFrom($apiRoutesPath);
        }

        // Scoped Swagger/OpenAPI docs for this module at /docs/account.
        if (class_exists(\Dedoc\Scramble\Scramble::class)) {
            \Dedoc\Scramble\Scramble::registerApi('account', [
                'api_path' => 'api/accounting',
                'info' => ['version' => \Composer\InstalledVersions::getPrettyVersion('zerp/account') ?? '1.0.0', 'description' => 'Zerp Accounting module REST API for mobile and third-party clients.'],
                'ui' => ['title' => 'Zerp Accounting API'],
            ])->expose(ui: '/docs/account', document: '/docs/account.json');
        }

        $migrationsPath = __DIR__.'/../Database/Migrations';
        if (is_dir($migrationsPath)) {
            $this->loadMigrationsFrom($migrationsPath);
        }

        // Whitelist orderBy against real table columns + asc|desc so a crafted
        // ?sort=/?direction= cannot be interpolated into SQL. Fixes zerp-pk/account#3.
        Builder::macro('sortSafe', function ($sort, $direction = null, $defaultColumn = 'created_at', $defaultDirection = 'desc') {
            $table = $this->getModel()->getTable();
            $column = ($sort && Schema::hasColumn($table, $sort)) ? $sort : $defaultColumn;
            $direction = in_array(strtolower((string) $direction), ['asc', 'desc'], true)
                ? strtolower($direction)
                : $defaultDirection;

            return $this->orderBy($column, $direction);
        });
    }

    public function register(): void
    {
        $this->app->register(EventServiceProvider::class);
    }
}