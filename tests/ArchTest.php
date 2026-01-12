<?php

declare(strict_types=1);

arch('it will not use debugging functions')
    ->expect(['dd', 'dump', 'ray', 'var_dump', 'print_r'])
    ->each->not->toBeUsed();

arch('strict types are enforced')
    ->expect('Skylence\FilamentContextSidebar')
    ->toUseStrictTypes();

arch('contracts are interfaces')
    ->expect('Skylence\FilamentContextSidebar\Contracts')
    ->toBeInterfaces();

arch('enums are enums')
    ->expect('Skylence\FilamentContextSidebar\Enums')
    ->toBeEnums();

arch('concerns are traits')
    ->expect('Skylence\FilamentContextSidebar\Concerns')
    ->toBeTraits();
