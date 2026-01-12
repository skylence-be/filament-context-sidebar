<?php

declare(strict_types=1);

use Skylence\FilamentContextSidebar\Concerns\HasContextSidebar;
use Skylence\FilamentContextSidebar\ContextSidebar;

it('trait has hasSidebar enabled by default', function (): void {
    $reflection = new ReflectionClass(HasContextSidebar::class);
    $props = $reflection->getDefaultProperties();

    expect($props['hasSidebar'])->toBeTrue();
});

it('bootHasContextSidebar sets proxy view', function (): void {
    $class = new class
    {
        use HasContextSidebar;

        public ?string $view = null;
    };

    $class->bootHasContextSidebar();

    expect($class->view)->toBe('filament-context-sidebar::proxy');
});

it('getSidebar calls static sidebar method', function (): void {
    $class = new class
    {
        use HasContextSidebar;

        public static function sidebar(): ContextSidebar
        {
            return ContextSidebar::make()->title('Test Title');
        }
    };

    $sidebar = $class->getSidebar();

    expect($sidebar)->toBeInstanceOf(ContextSidebar::class)
        ->and($sidebar->getTitle())->toBe('Test Title');
});
