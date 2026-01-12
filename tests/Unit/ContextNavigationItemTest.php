<?php

declare(strict_types=1);

use Filament\Navigation\NavigationItem;
use Skylence\FilamentContextSidebar\ContextNavigationItem;

it('can be instantiated with make', function (): void {
    $item = ContextNavigationItem::make();

    expect($item)->toBeInstanceOf(ContextNavigationItem::class);
});

it('extends NavigationItem', function (): void {
    $item = ContextNavigationItem::make();

    expect($item)->toBeInstanceOf(NavigationItem::class);
});

it('can set and get label', function (): void {
    $item = ContextNavigationItem::make()->label('My Label');

    expect($item->getLabel())->toBe('My Label');
});

it('does not translate label by default', function (): void {
    $item = ContextNavigationItem::make()->label('test.label');

    expect($item->getLabel())->toBe('test.label');
});

it('supports fluent translateLabel method', function (): void {
    $item = ContextNavigationItem::make()
        ->label('My Label')
        ->translateLabel(false);

    expect($item)->toBeInstanceOf(ContextNavigationItem::class)
        ->and($item->getLabel())->toBe('My Label');
});

it('can enable translation with translateLabel', function (): void {
    $item = ContextNavigationItem::make()
        ->label('My Label')
        ->translateLabel(true);

    expect($item)->toBeInstanceOf(ContextNavigationItem::class);
});

it('has translateLabel property set when enabled', function (): void {
    $item = ContextNavigationItem::make()
        ->label('My Label')
        ->translateLabel();

    // Verify the method is chainable and returns the instance
    expect($item)->toBeInstanceOf(ContextNavigationItem::class);
});

it('can set url', function (): void {
    $item = ContextNavigationItem::make()
        ->label('Test')
        ->url('https://example.com');

    expect($item->getUrl())->toBe('https://example.com');
});

it('can set icon', function (): void {
    $item = ContextNavigationItem::make()
        ->label('Test')
        ->icon('heroicon-o-home');

    expect($item->getIcon())->toBe('heroicon-o-home');
});

it('can set group', function (): void {
    $item = ContextNavigationItem::make()
        ->label('Test')
        ->group('Settings');

    expect($item->getGroup())->toBe('Settings');
});

it('can set sort order', function (): void {
    $item = ContextNavigationItem::make()
        ->label('Test')
        ->sort(5);

    expect($item->getSort())->toBe(5);
});

it('can set visibility', function (): void {
    $item = ContextNavigationItem::make()
        ->label('Test')
        ->visible(false);

    expect($item->isVisible())->toBeFalse();
});

it('is visible by default', function (): void {
    $item = ContextNavigationItem::make()
        ->label('Test');

    expect($item->isVisible())->toBeTrue();
});

it('can set active state', function (): void {
    $item = ContextNavigationItem::make()
        ->label('Test')
        ->isActiveWhen(fn (): bool => true);

    expect($item->isActive())->toBeTrue();
});

it('supports fluent interface for multiple properties', function (): void {
    $item = ContextNavigationItem::make()
        ->label('Dashboard')
        ->icon('heroicon-o-home')
        ->url('/dashboard')
        ->group('Main')
        ->sort(1)
        ->visible(true)
        ->translateLabel(false);

    expect($item)
        ->getLabel()->toBe('Dashboard')
        ->getIcon()->toBe('heroicon-o-home')
        ->getUrl()->toBe('/dashboard')
        ->getGroup()->toBe('Main')
        ->getSort()->toBe(1)
        ->isVisible()->toBeTrue();
});

it('can set badge', function (): void {
    $item = ContextNavigationItem::make()
        ->label('Test')
        ->badge('5');

    expect($item->getBadge())->toBe('5');
});
