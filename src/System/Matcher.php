<?php

namespace Mpietrucha\Regexp\System;

use Illuminate\Support\Collection;

class Matcher
{
    public static function all(string $regexp, string $source): array
    {
        preg_match_all($regexp, $source, $matches);

        return head($matches) ?? [];
    }
}
