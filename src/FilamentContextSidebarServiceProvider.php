<?php

declare(strict_types=1);

namespace Skylence\FilamentContextSidebar;

use Filament\Support\Assets\Css;
use Filament\Support\Facades\FilamentAsset;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class FilamentContextSidebarServiceProvider extends PackageServiceProvider
{
    public static string $name = 'filament-context-sidebar';

    public function configurePackage(Package $package): void
    {
        $package
            ->name(static::$name)
            ->hasConfigFile()
            ->hasViews()
            ->hasViewComponents('filament-context-sidebar')
            ->hasAssets();
    }

    public function packageBooted(): void
    {
        FilamentAsset::register(
            assets: [
                Css::make('filament-context-sidebar', __DIR__.'/../resources/dist/app.css'),
            ],
            package: 'skylence/filament-context-sidebar'
        );
    }
}
