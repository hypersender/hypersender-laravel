<?php

namespace Hypersender;

use Hypersender\Contracts\SmsWebhookJobInterface;
use Hypersender\Contracts\WhatsappWebhookJobInterface;
use Hypersender\Services\HypersenderSmsService;
use Hypersender\Services\HypersenderWhatsappService;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class HypersenderServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('hypersender-config')
            ->hasConfigFile()
            ->hasRoutes('webhook');

        $this->publishes([
            __DIR__.'/../config/hypersender-config.php' => config_path('hypersender-config.php'),
        ], 'hypersender-config');
    }

    public function packageRegistered(): void
    {
        $this->app->singleton(HypersenderManager::class);

        $this->app->singleton(HypersenderWhatsappService::class);
        $this->app->singleton(HypersenderSmsService::class);

        $this->app->bind(WhatsappWebhookJobInterface::class, config('hypersender-config.whatsapp_webhook_job'));

        $this->app->bind(SmsWebhookJobInterface::class, config('hypersender-config.sms_webhook_job'));
    }
}
