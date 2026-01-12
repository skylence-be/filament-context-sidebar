<?php

declare(strict_types=1);

namespace Skylence\FilamentContextSidebar;

use Closure;
use Filament\Navigation\NavigationGroup;
use Filament\Support\Concerns\EvaluatesClosures;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
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
        return $this->evaluate($this->title);
    }

    public function description(string|Closure $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->evaluate($this->description);
    }

    public function descriptionCopyable(bool|Closure $copyable = true): static
    {
        $this->descriptionCopyable = $copyable;

        return $this;
    }

    public function isDescriptionCopyable(): bool
    {
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
        return collect($this->navigationItems)
            ->filter(fn (ContextNavigationItem $item): bool => $item->isVisible())
            ->sortBy(fn (ContextNavigationItem $item): int => $item->getSort())
            ->groupBy(fn (ContextNavigationItem $item): ?string => $item->getGroup())
            ->map(function (Collection $items, ?string $groupIndex): NavigationGroup {
                if (blank($groupIndex)) {
                    return NavigationGroup::make()->items($items);
                }

                $registeredGroup = collect([])
                    ->first(function (NavigationGroup|string $registeredGroup, string|int $registeredGroupIndex) use ($groupIndex) {
                        if ($registeredGroupIndex === $groupIndex) {
                            return true;
                        }

                        if ($registeredGroup === $groupIndex) {
                            return true;
                        }

                        if (! $registeredGroup instanceof NavigationGroup) {
                            return false;
                        }

                        return $registeredGroup->getLabel() === $groupIndex;
                    });

                if ($registeredGroup instanceof NavigationGroup) {
                    return $registeredGroup->items($items);
                }

                return NavigationGroup::make($registeredGroup ?? $groupIndex)
                    ->items($items);
            })
            ->sortBy(function (NavigationGroup $group, ?string $groupIndex): int {
                if (blank($group->getLabel())) {
                    return -1;
                }

                $registeredGroups = [];
                $groupsToSearch = $registeredGroups;

                if (Arr::first($registeredGroups) instanceof NavigationGroup) {
                    $groupsToSearch = [
                        ...array_keys($registeredGroups),
                        ...array_map(fn (NavigationGroup $registeredGroup): string => $registeredGroup->getLabel(), array_values($registeredGroups)),
                    ];
                }

                $sort = array_search($groupIndex, $groupsToSearch, true);

                if ($sort === false) {
                    return count($registeredGroups);
                }

                return $sort;
            })
            ->all();
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
