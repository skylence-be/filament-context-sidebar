<?php

declare(strict_types=1);

use Skylence\FilamentContextSidebar\FilamentContextSidebarServiceProvider;
use Spatie\LaravelPackageTools\PackageServiceProvider;

it('extends PackageServiceProvider', function (): void {
    $provider = app(FilamentContextSidebarServiceProvider::class, ['app' => app()]);

    expect($provider)->toBeInstanceOf(PackageServiceProvider::class);
});

it('has correct package name', function (): void {
    expect(FilamentContextSidebarServiceProvider::$name)->toBe('filament-context-sidebar');
});
