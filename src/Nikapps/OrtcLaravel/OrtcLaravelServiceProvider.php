<?php namespace Nikapps\OrtcLaravel;

use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\ServiceProvider;

class OrtcLaravelServiceProvider extends ServiceProvider
{

    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->package('nikapps/ortc-laravel');

        AliasLoader::getInstance()->alias(
            'Ortc',
            'Nikapps\OrtcLaravel\OrtcLaravelFacade'
        );
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('Ortc', function ($app) {
            $config = $app['config'];
            return new OrtcLaravelFactory($config);
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return array();
    }
}
