<?php namespace EduardoAVargas\Pipefy;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\ServiceProvider;

class PipefyServiceProvider extends ServiceProvider {

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        // Publish Configuration File to base Path.
        $this->publishes([
            __DIR__.'/config/pipefy.php' => base_path('config/pipefy.php'),
            __DIR__ . '/migrations' => $this->app->databasePath() . '/migrations/pipefy'
        ]);
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->registerFactory($this->app);
        $this->registerManager($this->app);
        $this->registerRoutes($this->app);
    }

    /**
     * Register the factory class.
     *
     * @param \Illuminate\Contracts\Foundation\Application $app
     *
     * @return void
     */
    protected function registerFactory(Application $app)
    {
        $app->singleton('pipefy.factory', function () {
            return new Factories\PipefyFactory();
        });

        $app->alias('pipefy.factory', 'EduardoAVargas\Pipefy\Factories\PipefyFactory');
    }

    /**
     * Register the manager class.
     *
     * @param \Illuminate\Contracts\Foundation\Application $app
     *
     * @return void
     */
    protected function registerManager(Application $app)
    {
        $app->singleton('pipefy', function ($app) {
            $config = $app['config'];
            $factory = $app['pipefy.factory'];

            return new Pipefy($config, $factory);
        });

        $app->alias('pipefy', 'EduardoAVargas\Pipefy\Pipefy');
    }

    /**
     * Get the routes services provided by the provider.
     *
     * @return routes
     */
    protected function registerRoutes(Application $app) {
     /*   $app['router']->group(['namespace' => 'EduardoAVargas\Pipefy\Http\Controllers', "prefix" => "pipefy"], function () {
            require __DIR__.'/routes.php';
        });*/
    }


    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [
            'pipefy',
            'pipefy.factory',
        ];
    }
}
