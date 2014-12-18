<?php

namespace Heimonsy;

use Illuminate\Support\ServiceProvider;

class HipChatServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bindShared('hipchat', function ($app) {
            return new HipChat($app['config']['hipchat.token'], $app['config']['hipchat.room']);
        });
    }
}
