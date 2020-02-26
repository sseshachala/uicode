<?php

namespace Orchestra\Foundation\Providers;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;
use Orchestra\Foundation\Auth\User;
use Orchestra\Foundation\Publisher\Filesystem;
use Orchestra\Foundation\Publisher\PublisherManager;
use Orchestra\Model\Role;

class SupportServiceProvider extends ServiceProvider implements DeferrableProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->registerPublisher();

        $this->registerFilesystemPublisher();

        $this->registerRoleEloquent();

        $this->registerUserEloquent();
    }

    /**
     * Register the service provider for publisher.
     *
     * @return void
     */
    protected function registerPublisher(): void
    {
        $this->app->singleton('orchestra.publisher', static function (Application $app) {
            $memory = $app->make('orchestra.platform.memory');

            return (new PublisherManager($app))->attach($memory);
        });
    }

    /**
     * Register the service provider for filesystem publisher.
     *
     * @return void
     */
    protected function registerFilesystemPublisher(): void
    {
        $this->app->singleton('orchestra.publisher.filesystem', static function (Application $app) {
            return new Filesystem($app);
        });
    }

    /**
     * Register the service provider for user.
     *
     * @return void
     */
    protected function registerRoleEloquent(): void
    {
        $this->app->bind('orchestra.role', static function () {
            return Role::hs();
        });
    }

    /**
     * Register the service provider for user.
     *
     * @return void
     */
    protected function registerUserEloquent(): void
    {
        $this->app->bind('orchestra.user', static function () {
            return User::hs();
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [
            'orchestra.publisher', 'orchestra.role', 'orchestra.user',
        ];
    }
}
