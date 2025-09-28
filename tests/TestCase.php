<?php

namespace Hypersender\Hypersender\Tests;

use Hypersender\Hypersender\HypersenderServiceProvider;
use Illuminate\Database\Eloquent\Factories\Factory;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();

        Factory::guessFactoryNamesUsing(
            fn (string $modelName) => 'Hypersender\\Hypersender\\Database\\Factories\\'.class_basename($modelName).'Factory'
        );
    }

    protected function getPackageProviders($app)
    {
        return [
            HypersenderServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app)
    {
        config()->set('database.default', 'testing');

        config()->set('hypersender-laravel.base_url', 'https://app.hypersender.com/api/whatsapp/v1');
        config()->set('hypersender-laravel.instance_id', 'test-instance');
        config()->set('hypersender-laravel.whatsapp_webhook_authorization_secret', 'x-whatsapp-webhook');
    }
}
