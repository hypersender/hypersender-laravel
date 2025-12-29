<?php

namespace Hypersender\Tests;

use Hypersender\HypersenderServiceProvider;
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
        config()->set('hypersender-config.whatsapp_base_url', env('HYPERSENDER_WHATSAPP_BASE_URL', 'https://app.hypersender.com/api/whatsapp/v2'));

        /* WhatsApp */
        config()->set('hypersender-config.whatsapp_instance_id', 'test-instance');
        config()->set('hypersender-config.whatsapp_webhook_authorization_secret', 'x-whatsapp-webhook');

        /* SMS */
        config()->set('hypersender-config.sms_instance_id', 'test-instance');
        config()->set('hypersender-config.sms_webhook_authorization_secret', 'x-sms-webhook');

        /* OTP */
        config()->set('hypersender-config.otp_base_url', env('HYPERSENDER_OTP_BASE_URL', 'https://app.hypersender.com/api/otp/v2'));
        config()->set('hypersender-config.otp_api_key', 'test-api-key');
        config()->set('hypersender-config.otp_instance_id', 'test-otp-instance');
    }
}
