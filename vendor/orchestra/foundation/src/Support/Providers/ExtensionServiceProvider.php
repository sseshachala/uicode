<?php

namespace Orchestra\Foundation\Support\Providers;

use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;

class ExtensionServiceProvider extends ServiceProvider implements DeferrableProvider
{
    /**
     * Available orchestra extensions.
     *
     * @var array
     */
    protected $extensions = [];

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        $finder = $this->app->make('orchestra.extension.finder');

        foreach ($this->extensions as $name => $path) {
            if (\is_numeric($name)) {
                $finder->addPath($path);
            } else {
                $finder->registerExtension($name, $path);
            }
        }
    }

    /**
     * Get the events that trigger this service provider to register.
     *
     * @return array
     */
    public function when()
    {
        return ['orchestra.extension: detecting'];
    }
}
