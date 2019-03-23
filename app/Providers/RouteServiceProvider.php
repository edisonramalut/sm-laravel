<?php

namespace App\Providers;

use App\Http\Controllers\HomeController;
use Illuminate\Routing\Controller;
use Illuminate\Routing\Router;
use Illuminate\Routing\RouteRegistrar;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * Default prefix for controller defined routes in this service provider.
     *
     * @var string
     */
    protected $prefix = '';

    /**
     * The controllers to load routes definitions from.
     * We define all the controller classes here before
     * using the routes function.
     *
     * @var array
     */
    protected $controllers = [ HomeController::class ];

    /**
     * This namespace is applied to your controller routes.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = 'App\Http\Controllers';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @param RouteRegistrar $routeRegistrar
     * @return void
     */
    public function boot(RouteRegistrar $routeRegistrar)
    {
        //
        parent::boot($routeRegistrar);
    }

    /**
     * @param RouteRegistrar $routeRegistrar
     * @throws \Exception
     */
    protected function loadRoutes(RouteRegistrar $routeRegistrar)
    {
        parent::loadRoutes($routeRegistrar);
        $this->loadRoutesFromControllers($routeRegistrar);
    }

    /**
     * Define the routes for the application.
     *
     * @return void
     */
    public function map()
    {
        $this->mapApiRoutes();

        $this->mapWebRoutes();

        //
    }

    /**
     * Define the "web" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     *
     * @return void
     */
    protected function mapWebRoutes()
    {
        Route::middleware('web')
             ->namespace($this->namespace)
             ->group(base_path('routes/web.php'));
    }

    /**
     * Define the "api" routes for the application.
     *
     * These routes are typically stateless.
     *
     * @return void
     */
    protected function mapApiRoutes()
    {
        Route::prefix('api')
             ->middleware('api')
             ->namespace($this->namespace)
             ->group(base_path('routes/api.php'));
    }

    /**
     * Define the routes for the application.
     *
     * @param  RouteRegistrar  $routeRegistrar
     * @throws \Exception
     */
    protected function loadRoutesFromControllers(RouteRegistrar $routeRegistrar)
    {
        foreach ($this->controllers as $controller) {
            $controller = $this->app->make($controller);

            if (!is_a($controller, Controller::class)) {
                throw new \Exception('Controller class must extend ' . Controller::class);
            }


            $routeRegistrar
                ->prefix($this->prefix . $controller->prefix)
                ->group(function (Router $router) use ($controller) {
                    $controller->routes($router);
                });
        }
    }
}
