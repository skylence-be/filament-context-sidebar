<?php

declare(strict_types=1);

use Filament\Navigation\NavigationGroup;
use Skylence\FilamentContextSidebar\ContextNavigationItem;
use Skylence\FilamentContextSidebar\ContextSidebar;
use Skylence\FilamentContextSidebar\Contracts\Makeable;
use Skylence\FilamentContextSidebar\Enums\PageNavigationLayout;

it('can be instantiated with make', function (): void {
    $sidebar = ContextSidebar::make();

    expect($sidebar)->toBeInstanceOf(ContextSidebar::class);
});

it('implements Makeable interface', function (): void {
    $sidebar = ContextSidebar::make();

    expect($sidebar)->toBeInstanceOf(Makeable::class);
});

it('can set and get title', function (): void {
    $sidebar = ContextSidebar::make()->title('My Title');

    expect($sidebar->getTitle())->toBe('My Title');
});

it('can set title with closure', function (): void {
    $sidebar = ContextSidebar::make()->title(fn (): string => 'Dynamic Title');

    expect($sidebar->getTitle())->toBe('Dynamic Title');
});

it('returns null when no title is set', function (): void {
    $sidebar = ContextSidebar::make();

    expect($sidebar->getTitle())->toBeNull();
});

it('can set and get description', function (): void {
    $sidebar = ContextSidebar::make()->description('My Description');

    expect($sidebar->getDescription())->toBe('My Description');
});

it('can set description with closure', function (): void {
    $sidebar = ContextSidebar::make()->description(fn (): string => 'Dynamic Description');

    expect($sidebar->getDescription())->toBe('Dynamic Description');
});

it('returns null when no description is set', function (): void {
    $sidebar = ContextSidebar::make();

    expect($sidebar->getDescription())->toBeNull();
});

it('can set description as copyable', function (): void {
    $sidebar = ContextSidebar::make()->descriptionCopyable();

    expect($sidebar->isDescriptionCopyable())->toBeTrue();
});

it('can set description copyable with closure', function (): void {
    $sidebar = ContextSidebar::make()->descriptionCopyable(fn (): bool => true);

    expect($sidebar->isDescriptionCopyable())->toBeTrue();
});

it('can disable description copyable', function (): void {
    $sidebar = ContextSidebar::make()
        ->descriptionCopyable()
        ->descriptionCopyable(false);

    expect($sidebar->isDescriptionCopyable())->toBeFalse();
});

it('defaults to not copyable description', function (): void {
    $sidebar = ContextSidebar::make();

    expect($sidebar->isDescriptionCopyable())->toBeFalse();
});

it('defaults to sidebar layout', function (): void {
    $sidebar = ContextSidebar::make();

    expect($sidebar->getLayout())->toBe(PageNavigationLayout::Sidebar);
});

it('can set layout to topbar', function (): void {
    $sidebar = ContextSidebar::make()->layout(PageNavigationLayout::Topbar);

    expect($sidebar->getLayout())->toBe(PageNavigationLayout::Topbar);
});

it('can use sidebarNavigation helper', function (): void {
    $sidebar = ContextSidebar::make()
        ->topbarNavigation()
        ->sidebarNavigation();

    expect($sidebar->getLayout())->toBe(PageNavigationLayout::Sidebar);
});

it('can use topbarNavigation helper', function (): void {
    $sidebar = ContextSidebar::make()->topbarNavigation();

    expect($sidebar->getLayout())->toBe(PageNavigationLayout::Topbar);
});

it('can set navigation items', function (): void {
    $items = [
        ContextNavigationItem::make()->label('Item 1'),
        ContextNavigationItem::make()->label('Item 2'),
    ];

    $sidebar = ContextSidebar::make()->navigationItems($items);
    $groups = $sidebar->getNavigationItems();

    expect($groups)->toHaveCount(1)
        ->and($groups[0])->toBeInstanceOf(NavigationGroup::class);
});

it('filters out invisible items', function (): void {
    $items = [
        ContextNavigationItem::make()->label('Visible'),
        ContextNavigationItem::make()->label('Hidden')->visible(false),
    ];

    $sidebar = ContextSidebar::make()->navigationItems($items);
    $groups = $sidebar->getNavigationItems();

    expect($groups)->toHaveCount(1);

    $groupItems = $groups[0]->getItems();
    expect($groupItems)->toHaveCount(1)
        ->and($groupItems[0]->getLabel())->toBe('Visible');
});

it('sorts items by sort order', function (): void {
    $items = [
        ContextNavigationItem::make()->label('Third')->sort(3),
        ContextNavigationItem::make()->label('First')->sort(1),
        ContextNavigationItem::make()->label('Second')->sort(2),
    ];

    $sidebar = ContextSidebar::make()->navigationItems($items);
    $groups = $sidebar->getNavigationItems();
    $groupItems = $groups[0]->getItems();

    expect($groupItems[0]->getLabel())->toBe('First')
        ->and($groupItems[1]->getLabel())->toBe('Second')
        ->and($groupItems[2]->getLabel())->toBe('Third');
});

it('groups items by group name', function (): void {
    $items = [
        ContextNavigationItem::make()->label('Item 1')->group('Group A'),
        ContextNavigationItem::make()->label('Item 2')->group('Group B'),
        ContextNavigationItem::make()->label('Item 3')->group('Group A'),
    ];

    $sidebar = ContextSidebar::make()->navigationItems($items);
    $groups = $sidebar->getNavigationItems();

    expect($groups)->toHaveCount(2);
});

it('places ungrouped items first', function (): void {
    $items = [
        ContextNavigationItem::make()->label('Grouped')->group('Settings'),
        ContextNavigationItem::make()->label('Ungrouped'),
    ];

    $sidebar = ContextSidebar::make()->navigationItems($items);
    $groups = $sidebar->getNavigationItems();

    expect($groups)->toHaveCount(2)
        ->and($groups[0]->getLabel())->toBeNull()
        ->and($groups[1]->getLabel())->toBe('Settings');
});

it('returns empty array when no items', function (): void {
    $sidebar = ContextSidebar::make()->navigationItems([]);

    expect($sidebar->getNavigationItems())->toBe([]);
});

it('supports fluent interface', function (): void {
    $sidebar = ContextSidebar::make()
        ->title('Title')
        ->description('Description')
        ->descriptionCopyable()
        ->topbarNavigation();

    expect($sidebar)
        ->getTitle()->toBe('Title')
        ->getDescription()->toBe('Description')
        ->isDescriptionCopyable()->toBeTrue()
        ->getLayout()->toBe(PageNavigationLayout::Topbar);
});

it('can chain all methods fluently', function (): void {
    $items = [
        ContextNavigationItem::make()->label('Test'),
    ];

    $sidebar = ContextSidebar::make()
        ->title('My Sidebar')
        ->description('My Description')
        ->descriptionCopyable()
        ->layout(PageNavigationLayout::Topbar)
        ->navigationItems($items)
        ->sidebarNavigation();

    expect($sidebar)->toBeInstanceOf(ContextSidebar::class)
        ->and($sidebar->getLayout())->toBe(PageNavigationLayout::Sidebar);
});

it('handles closure that returns false for copyable', function (): void {
    $sidebar = ContextSidebar::make()->descriptionCopyable(fn (): bool => false);

    expect($sidebar->isDescriptionCopyable())->toBeFalse();
});
