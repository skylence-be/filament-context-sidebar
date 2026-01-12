<?php

declare(strict_types=1);

use Filament\Contracts\Plugin;
use Filament\Panel;
use Skylence\FilamentContextSidebar\FilamentContextSidebarPlugin;

it('can get plugin id', function (): void {
    $plugin = new FilamentContextSidebarPlugin;

    expect($plugin->getId())->toBe('filament-context-sidebar');
});

it('implements Plugin interface', function (): void {
    $plugin = new FilamentContextSidebarPlugin;

    expect($plugin)->toBeInstanceOf(Plugin::class);
});

it('can be instantiated with make', function (): void {
    $plugin = FilamentContextSidebarPlugin::make();

    expect($plugin)->toBeInstanceOf(FilamentContextSidebarPlugin::class);
});

it('register method accepts Panel', function (): void {
    $plugin = new FilamentContextSidebarPlugin;
    $panel = $this->mock(Panel::class);

    $plugin->register($panel);

    expect(true)->toBeTrue(); // No exception thrown
});

it('boot method accepts Panel', function (): void {
    $plugin = new FilamentContextSidebarPlugin;
    $panel = $this->mock(Panel::class);

    $plugin->boot($panel);

    expect(true)->toBeTrue(); // No exception thrown
});
