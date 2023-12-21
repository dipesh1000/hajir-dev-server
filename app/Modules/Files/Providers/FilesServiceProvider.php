<?php

namespace Files\Providers;

use Files\Repositories\FileInterface;
use Files\Repositories\FileRepository;
use Illuminate\Support\Facades\File;
use Illuminate\Support\ServiceProvider;

class FilesServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(FileInterface::class, FileRepository::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $moduleName = "Files";
        $ds = DIRECTORY_SEPARATOR;
        config([
            'filesroute' => File::getRequire(loadConfig('route.php', $moduleName)),
        ]);
        $this->loadRoutesFrom(loadRoutes('admin.php', $moduleName));
        $this->loadRoutesFrom(loadRoutes('web.php', $moduleName));
        $this->loadMigrationsFrom(loadMigrations($moduleName));
        $this->loadViewsFrom(loadViews($moduleName), $moduleName);
    }
}
