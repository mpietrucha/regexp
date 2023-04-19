<?php

namespace Mpietrucha\Regexp\Contracts;

interface ProviderInterface
{
    public function name(): string;

    public function handle(string $source): array;
}
