<?php

namespace Hypersender\Hypersender;

use Hypersender\Hypersender\Commands\HypersenderCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class HypersenderServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('hypersender-laravel')
            ->hasConfigFile()
            ->hasViews()
            ->hasMigration('create_hypersender_laravel_table')
            ->hasCommand(HypersenderCommand::class);
    }
}
