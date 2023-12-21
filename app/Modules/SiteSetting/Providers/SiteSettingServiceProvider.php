<?php

namespace SiteSetting\Providers;

use SiteSetting\Repositories\SiteSettingInterface;
use SiteSetting\Repositories\SiteSettingRepository;
use Illuminate\Support\Facades\File;
use Illuminate\Support\ServiceProvider;


class SiteSettingServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */

    public function register()
    {
        $this->app->bind(SiteSettingInterface::class,SiteSettingRepository::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $moduleName="SiteSetting";
        config([
            'sitesettingroute' => File::getRequire(loadConfig('route.php', $moduleName)),
        ]);
        $this->loadRoutesFrom(loadRoutes('admin.php', $moduleName));
        $this->loadMigrationsFrom(loadMigrations($moduleName));
        $this->loadViewsFrom(loadViews($moduleName), $moduleName);

    }
}
