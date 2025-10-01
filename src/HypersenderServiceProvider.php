<?php

namespace Hypersender\Hypersender;

use Hypersender\Hypersender\Clients\Sms\HypersenderSmsClient;
use Hypersender\Hypersender\Clients\Whatsapp\HypersenderWhatsappClient;
use Hypersender\Hypersender\Contracts\SmsWebhookJobInterface;
use Hypersender\Hypersender\Contracts\WhatsappWebhookJobInterface;
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
    }

    public function packageRegistered(): void
    {
        $this->app->singleton(HypersenderWhatsappClient::class, function ($app) {
            return new HypersenderWhatsappClient;
        });

        $this->app->singleton(HypersenderSmsClient::class, function ($app) {
            return new HypersenderSmsClient;
        });

        $this->app->singleton(HypersenderManager::class, function ($app) {
            return new HypersenderManager(
                $app->make(HypersenderWhatsappClient::class),
                $app->make(HypersenderSmsClient::class),
            );
        });

        $this->app->bind(WhatsappWebhookJobInterface::class, config('hypersender-laravel.whatsapp_webhook_job'));

        $this->app->bind(SmsWebhookJobInterface::class, config('hypersender-laravel.sms_webhook_job'));
    }
}
