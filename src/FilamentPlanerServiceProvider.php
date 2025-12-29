<?php

namespace LabaPawel\FilamentPlaner;

use Filament\Support\Assets\Css;
use Filament\Support\Assets\Js;
use Filament\Support\Facades\FilamentAsset;
use Illuminate\Support\ServiceProvider;

class FilamentPlanerServiceProvider extends ServiceProvider
{
    public function boot()
    {

        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'filament-planer');
        $this->loadTranslationsFrom(__DIR__ . '/../resources/lang', 'filament-planer');

        $this->publishes([
            __DIR__ . '/../config/filament-planer.php' => config_path('filament-planer.php'),
        ], 'filament-planer-config');

        $this->publishes([
            __DIR__ . '/../resources/views' => resource_path('views/vendor/filament-planer'),
        ], 'filament-planer-views');

        $this->publishes([
            __DIR__ . '/../resources/lang' => resource_path('lang/vendor/filament-planer'),
        ], 'filament-planer-translations');

        FilamentAsset::register([
            Css::make('filament-planer', __DIR__ . '/../resources/css/filament-planer.css'),
        ], 'labapawel/filament-planer');
    }

    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/filament-planer.php', 'filament-planer');
    }
}
