<?php

namespace Hypersender\Hypersender;

use Hypersender\Hypersender\Clients\Sms\HypersenderSmsClient;
use Hypersender\Hypersender\Clients\Whatsapp\HypersenderWhatsappClient;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class HypersenderServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('hypersender-laravel')
            ->hasConfigFile();
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
    }
}
