<?php

namespace Candidate\Providers;

use Candidate\Repositories\auth\AuthCandiadateInterface;
use Candidate\Repositories\auth\AuthCandidateInterface;
use Candidate\Repositories\auth\AuthCandidateRepository;
use Candidate\Repositories\candidate\CandidateInterface;
use Candidate\Repositories\candidate\CandidateRepository;
use Illuminate\Support\Facades\File;
use Illuminate\Support\ServiceProvider;


class CandidateServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */

    public function register()
    {

        $this->app->bind(AuthCandidateInterface::class,AuthCandidateRepository::class);

    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $moduleName="Candidate";
        config([
            'candidateRoute' => File::getRequire(loadConfig('route.php', $moduleName)),
        ]);
        $this->loadRoutesFrom(loadRoutes('admin.php', $moduleName));
        $this->loadRoutesFrom(loadRoutes('api.php', $moduleName));
        $this->loadMigrationsFrom(loadMigrations($moduleName));
        $this->loadViewsFrom(loadViews($moduleName), $moduleName);

    }
}
