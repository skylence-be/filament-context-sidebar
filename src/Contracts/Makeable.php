<?php

declare(strict_types=1);

namespace Skylence\FilamentContextSidebar\Contracts;

interface Makeable
{
    public static function make(): static;
}
