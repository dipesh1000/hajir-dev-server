<?php

namespace Employer\Providers;

use Employer\Repositories\auth\AuthEmployerInterface;
use Employer\Repositories\auth\AuthEmployerRepository;
use Employer\Repositories\candidate\CandidateInterface;
use Employer\Repositories\candidate\CandidateRepository;
use Employer\Repositories\company\CompanyInterface;
use Employer\Repositories\company\CompanyRepository;
use Illuminate\Support\Facades\File;
use Illuminate\Support\ServiceProvider;


class EmployerServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */

    public function register()
    {

        $this->app->bind(AuthEmployerInterface::class,AuthEmployerRepository::class);
        $this->app->bind(CompanyInterface::class,CompanyRepository::class);
        $this->app->bind(CandidateInterface::class,CandidateRepository::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $moduleName="Employer";
        config([
            'employerRoute' => File::getRequire(loadConfig('route.php', $moduleName)),
        ]);
        $this->loadRoutesFrom(loadRoutes('admin.php', $moduleName));
        $this->loadRoutesFrom(loadRoutes('web.php', $moduleName));
        $this->loadRoutesFrom(loadRoutes('api.php', $moduleName));
        $this->loadMigrationsFrom(loadMigrations($moduleName));
        $this->loadViewsFrom(loadViews($moduleName), $moduleName);

    }
}
