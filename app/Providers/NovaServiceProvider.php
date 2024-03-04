<?php

namespace App\Providers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Nova\Dashboards\Main;
/*
 * Custom Metrics
 */

use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Nova;
use Laravel\Nova\NovaApplicationServiceProvider;
use Trosthelden\Export\Export;
use Trosthelden\Matching\Matching;
use App\User;
use Laravel\Nova\Observable;
use App\Observers\NovaUserObserver;

class NovaServiceProvider extends NovaApplicationServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        Observable::make(User::class, NovaUserObserver::class);

    }

    /**
     * Register the Nova routes.
     *
     * @return void
     */
    protected function routes()
    {
        Nova::routes()
                ->withAuthenticationRoutes()
                ->withPasswordResetRoutes()
                ->register();
    }

    /**
     * Register the Nova gate.
     *
     * This gate determines who can access Nova in non-local environments.
     *
     * @return void
     */
    protected function gate()
    {
        Gate::define('viewNova', function ($user) {
            return $user->has_nova_access;
        });
    }


    /**
     * Get the extra dashboards that should be displayed on the Nova dashboard.
     *
     * @return array
     */
    protected function dashboards()
    {
        return [
            new Main,
        ];
    }

    /**
     * Get the tools that should be listed in the Nova sidebar.
     *
     * @return array
     */
    public function tools()
    {
        return [
            (new Matching),
            (new Export),
            \Vyuldashev\NovaPermission\NovaPermissionTool::make(),
        ];
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
