<?php

namespace Thettler\ExtendedLocalization;

use Illuminate\Contracts\Foundation\Application;
use Livewire\Livewire;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Thettler\ExtendedLocalization\Commands\ExtendedLocalizationCommand;

class ExtendedLocalizationServiceProvider extends PackageServiceProvider
{
    public function packageRegistered()
    {
        $this->app->bind(TranslatableAttribute::class, function (Application $app, array $translations = []) {
            return new TranslatableAttribute($app['config']['extended-localization.language_enum'], $translations);
        });
    }

    public function packageBooted()
    {
        Livewire::propertySynthesizer(TranslatableAttributeSynth::class);
    }

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
            ->hasCommand(ExtendedLocalizationCommand::class);
    }
}
