<?php namespace Streams\Platform\Provider;

use Illuminate\Support\ServiceProvider;
use Streams\Platform\Foundation\Application;
use Streams\Platform\Foundation\ApplicationModel;

class ApplicationServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     */
    public function register()
    {
        $this->app->instance('streams.application', new Application(new ApplicationModel(), $this->app));

        app('config')->addNamespace('streams', __DIR__ . '/../../../../resources/config');

        app('streams.application')->locate();
    }
}
