<?php 

namespace Nikapps\OrtcLaravel;

use Illuminate\Support\ServiceProvider;

class OrtcLaravelServiceProvider extends ServiceProvider
{

    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    public function boot()
    {
        $this->app->make('Illuminate\Broadcasting\BroadcastManager')->extend(
                'realtime', function ($app, $config) {
            
            return new OrtcBroadcaster($app->make('Nikapps\OrtcLaravel\OrtcLaravelFactory'));
        });
    }
    
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('Ortc', function ($app) {
            $config = $app['config']['broadcasting']['connections']['realtime'];
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
        return ['Ortc'];
    }
}
