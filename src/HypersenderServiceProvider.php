<?php

namespace Hypersender\Hypersender;

use Hypersender\Hypersender\Contracts\SmsWebhookJobInterface;
use Hypersender\Hypersender\Contracts\WhatsappWebhookJobInterface;
use Hypersender\Hypersender\Services\HypersenderSmsService;
use Hypersender\Hypersender\Services\HypersenderWhatsappService;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class HypersenderServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('hypersender-laravel')
            ->hasConfigFile()
            ->hasRoutes('webhook');

        $this->publishes([
            __DIR__.'/../config/hypersender-laravel.php' => config_path('hypersender-laravel.php'),
        ], 'hypersender-laravel-config');
    }

    public function packageRegistered(): void
    {
        $this->app->singleton(HypersenderManager::class);

        $this->app->singleton(HypersenderWhatsappService::class);
        $this->app->singleton(HypersenderSmsService::class);

        $this->app->bind(WhatsappWebhookJobInterface::class, config('hypersender-laravel.whatsapp_webhook_job'));

        $this->app->bind(SmsWebhookJobInterface::class, config('hypersender-laravel.sms_webhook_job'));
    }
}
