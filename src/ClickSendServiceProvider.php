<?php

namespace Codemonkey76\ClickSend;

use Illuminate\Support\ServiceProvider;
use Illuminate\Foundation\AliasLoader;
use Codemonkey76\ClickSend\MessageInterface;
use Codemonkey76\ClickSend\ClickSendFacade;

class ClickSendServiceProvider extends ServiceProvider
{
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/config.php' => config_path('clicksend.php'),
            ], 'config');
        }
    }

    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/config.php', 'clicksend');

        $this->app->singleton(MessageInterface::class, function ($app) {
            return new ClickSend(
                $app['config']->get('clicksend.username'),
                $app['config']->get('clicksend.password'),
                $app['config']->get('clicksend.api_endpoint'));
        });

        $loader = AliasLoader::getInstance();
        $loader->alias('ClickSend', ClickSendFacade::class);
    }
}
