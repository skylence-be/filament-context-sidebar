<?php

declare(strict_types=1);

namespace Skylence\FilamentContextSidebar;

use Closure;
use Filament\Navigation\NavigationGroup;
use Filament\Support\Concerns\EvaluatesClosures;
use Skylence\FilamentContextSidebar\Contracts\Makeable;
use Skylence\FilamentContextSidebar\Enums\PageNavigationLayout;

class ContextSidebar implements Makeable
{
    use EvaluatesClosures;

    protected string|Closure|null $title = null;

    protected string|Closure|null $description = null;

    protected bool|Closure $descriptionCopyable = false;

    /** @var array<ContextNavigationItem> */
    protected array $navigationItems = [];

    protected PageNavigationLayout $layout = PageNavigationLayout::Sidebar;

    public static function make(): static
    {
        return new static;
    }

    public function title(string|Closure $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getTitle(): ?string
    {
        /** @var string|null */
        return $this->evaluate($this->title);
    }

    public function description(string|Closure $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getDescription(): ?string
    {
        /** @var string|null */
        return $this->evaluate($this->description);
    }

    public function descriptionCopyable(bool|Closure $copyable = true): static
    {
        $this->descriptionCopyable = $copyable;

        return $this;
    }

    public function isDescriptionCopyable(): bool
    {
        /** @var bool */
        return $this->evaluate($this->descriptionCopyable);
    }

    /**
     * @param  array<ContextNavigationItem>  $items
     */
    public function navigationItems(array $items): static
    {
        $this->navigationItems = $items;

        return $this;
    }

    /**
     * @return array<NavigationGroup>
     */
    public function getNavigationItems(): array
    {
        $items = collect($this->navigationItems)
            ->filter(fn (ContextNavigationItem $item): bool => $item->isVisible())
            ->sortBy(fn (ContextNavigationItem $item): int => $item->getSort());

        /** @var array<string, list<ContextNavigationItem>> $grouped */
        $grouped = [];
        foreach ($items as $item) {
            $rawGroup = $item->getGroup();
            $group = $rawGroup instanceof \UnitEnum ? $rawGroup->name : ($rawGroup ?? '');
            if (! isset($grouped[$group])) {
                $grouped[$group] = [];
            }
            $grouped[$group][] = $item;
        }

        $result = [];
        foreach ($grouped as $groupName => $groupItems) {
            $navigationGroup = blank($groupName)
                ? NavigationGroup::make()
                : NavigationGroup::make($groupName);

            $result[] = $navigationGroup->items($groupItems);
        }

        usort($result, function (NavigationGroup $a, NavigationGroup $b): int {
            $aLabel = $a->getLabel();
            $bLabel = $b->getLabel();

            if (blank($aLabel)) {
                return -1;
            }
            if (blank($bLabel)) {
                return 1;
            }

            return 0;
        });

        return $result;
    }

    public function layout(PageNavigationLayout $layout): static
    {
        $this->layout = $layout;

        return $this;
    }

    public function getLayout(): PageNavigationLayout
    {
        return $this->layout;
    }

    public function sidebarNavigation(): static
    {
        $this->layout = PageNavigationLayout::Sidebar;

        return $this;
    }

    public function topbarNavigation(): static
    {
        $this->layout = PageNavigationLayout::Topbar;

        return $this;
    }
}
