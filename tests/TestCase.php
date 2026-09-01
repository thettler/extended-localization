<?php

namespace Thettler\ExtendedLocalization\Tests;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Orchestra\Testbench\TestCase as Orchestra;
use Thettler\ExtendedLocalization\ExtendedLocalizationServiceProvider;

class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();

        Factory::guessFactoryNamesUsing(
            fn (string $modelName) => 'Thettler\\ExtendedLocalization\\Database\\Factories\\'.class_basename($modelName).'Factory'
        );
        $this->setUpDatabase();
    }

    protected function getPackageProviders($app)
    {
        return [
            ExtendedLocalizationServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app)
    {
        config()->set('database.default', 'testing');
        config()->set('app.key', 'base64:vXhjVvpkxSRTXdMx5euzLojCkn3+4q1xOBx9Pdp13K0=');

        /*
         foreach (\Illuminate\Support\Facades\File::allFiles(__DIR__ . '/../database/migrations') as $migration) {
            (include $migration->getRealPath())->up();
         }
         */
    }
    protected function setUpDatabase()
    {
        Schema::create('test_models', function (Blueprint $table) {
            $table->increments('id');
            $table->json('text');
            $table->json('text_nullable')->nullable();
            $table->timestamps();
        });

        Schema::create('test_models_castable', function (Blueprint $table) {
            $table->increments('id');
            $table->json('bool')->nullable();
            $table->json('date')->nullable();
            $table->json('array')->nullable();
            $table->json('collection')->nullable();
            $table->json('fluent')->nullable();
            $table->json('string')->nullable();
            $table->json('encrypted')->nullable();
            $table->timestamps();
        });
    }
}
