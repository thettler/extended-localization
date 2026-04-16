<?php

namespace Thettler\ExtendedLocalization;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Thettler\ExtendedLocalization\Commands\ExtendedLocalizationCommand;

class ExtendedLocalizationServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('extended-localization')
            ->hasConfigFile()
            ->hasViews()
            ->hasMigration('create_extended_localization_table')
            ->hasCommand(ExtendedLocalizationCommand::class);
    }
}
