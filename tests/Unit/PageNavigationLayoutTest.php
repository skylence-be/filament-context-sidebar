<?php

declare(strict_types=1);

use Skylence\FilamentContextSidebar\Enums\PageNavigationLayout;

it('has sidebar case', function (): void {
    expect(PageNavigationLayout::Sidebar->value)->toBe('sidebar');
});

it('has topbar case', function (): void {
    expect(PageNavigationLayout::Topbar->value)->toBe('topbar');
});

it('has exactly two cases', function (): void {
    expect(PageNavigationLayout::cases())->toHaveCount(2);
});
