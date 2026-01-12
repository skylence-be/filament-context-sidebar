<?php

declare(strict_types=1);

namespace Skylence\FilamentContextSidebar\Concerns;

use Exception;
use Filament\Pages\Page;
use ReflectionClass;
use Skylence\FilamentContextSidebar\ContextSidebar;

/**
 * @mixin Page
 */
trait HasContextSidebar
{
    /**
     * Activate or not the automatic sidebar to the page.
     * If you change it to FALSE then add manually the $view parameter.
     */
    public static bool $hasSidebar = true;

    /**
     * Register automatically view if available and activated.
     */
    public function bootHasContextSidebar(): void
    {
        if (static::$hasSidebar) {
            $this->view = 'filament-context-sidebar::proxy';
        }
    }

    /**
     * Return the view that will be used in the sidebar proxy.
     */
    public function getIncludedSidebarView(): string
    {
        if (is_subclass_of($this, Page::class)) {
            $props = collect(
                (new ReflectionClass($this))->getDefaultProperties()
            );

            if ($props->get('view')) {
                return $props->get('view');
            }
        }

        throw new Exception('No view detected for the Sidebar. Implement Filament\Pages\Page object with valid static $view');
    }

    public function getSidebar(): ContextSidebar
    {
        if (property_exists($this, 'resource')) {
            return static::getResource()::sidebar($this->record);
        }

        return static::sidebar();
    }

    /**
     * @return array<string, int>
     */
    public function getSidebarWidths(): array
    {
        return config('filament-context-sidebar.sidebar_width', [
            'sm' => 12,
            'md' => 3,
            'lg' => 3,
            'xl' => 3,
            '2xl' => 3,
        ]);
    }
}
