<?php

declare(strict_types=1);

namespace Skylence\FilamentContextSidebar;

use Filament\Navigation\NavigationItem;

class ContextNavigationItem extends NavigationItem
{
    protected bool $shouldTranslateLabel = false;

    public function translateLabel(bool $shouldTranslateLabel = true): static
    {
        $this->shouldTranslateLabel = $shouldTranslateLabel;

        return $this;
    }

    public function getLabel(): string
    {
        $label = parent::getLabel();

        if (! $this->shouldTranslateLabel) {
            return $label;
        }

        $translated = __($label);

        return is_string($translated) ? $translated : $label;
    }
}
