<?php

namespace ErlangParasu\DebugbarVscode;

use ErlangParasu\DebugbarVscode\Middleware\InjectDebugbarVscode;
use Illuminate\Contracts\Http\Kernel;
use Illuminate\Routing\Router;

class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(
            LaravelDebugbarVscode::class,
            function () {
                $debugbarvscode = new LaravelDebugbarVscode($this->app);
                return $debugbarvscode;
            }
        );

        $this->app->alias(LaravelDebugbarVscode::class, 'debugbarvscode');
    }

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerMiddleware(InjectDebugbarVscode::class);
    }

    /**
     * Get the active router.
     *
     * @return Router
     */
    protected function getRouter()
    {
        return $this->app['router'];
    }

    /**
     * Register the DebugbarVscode Middleware
     *
     * @param  string $middleware
     */
    protected function registerMiddleware($middleware)
    {
        $kernel = $this->app[Kernel::class];
        $kernel->pushMiddleware($middleware);
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['debugbarvscode'];
    }
}
