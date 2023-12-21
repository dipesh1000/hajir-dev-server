<?php

namespace SuperAdmin\Providers;

use SuperAdmin\Repositories\auth\AuthSuperAdminInterface;
use SuperAdmin\Repositories\auth\AuthSuperAdminRepository;
use SuperAdmin\Repositories\company\CompanyInterface;
use SuperAdmin\Repositories\company\CompanyRepository;
use Illuminate\Support\Facades\File;
use Illuminate\Support\ServiceProvider;


class SuperAdminServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */

    public function register()
    {

        $this->app->bind(AuthSuperAdminInterface::class,AuthSuperAdminRepository::class);
        $this->app->bind(CompanyInterface::class,CompanyRepository::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $moduleName="SuperAdmin";
        config([
            'superAdminRoute' => File::getRequire(loadConfig('route.php', $moduleName)),
        ]);
        $this->loadRoutesFrom(loadRoutes('admin.php', $moduleName));
        $this->loadRoutesFrom(loadRoutes('api.php', $moduleName));
        $this->loadMigrationsFrom(loadMigrations($moduleName));
        $this->loadViewsFrom(loadViews($moduleName), $moduleName);

    }
}
