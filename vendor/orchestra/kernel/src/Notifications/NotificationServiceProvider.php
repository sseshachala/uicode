<?php

namespace Orchestra\Notifications;

use Illuminate\Contracts\Container\Container;
use Illuminate\Contracts\Notifications\Dispatcher as DispatcherContract;
use Illuminate\Contracts\Notifications\Factory as FactoryContract;
use Illuminate\Notifications\ChannelManager as Manager;
use Illuminate\Notifications\NotificationServiceProvider as ServiceProvider;

class NotificationServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(ChannelManager::class, static function (Container $app) {
            return new ChannelManager($app);
        });

        $this->app->alias(ChannelManager::class, DispatcherContract::class);
        $this->app->alias(ChannelManager::class, FactoryContract::class);
        $this->app->alias(ChannelManager::class, Manager::class);
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [
            ChannelManager::class,
            DispatcherContract::class,
            FactoryContract::class,
            Manager::class,
        ];
    }
}
