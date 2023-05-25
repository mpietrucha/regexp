<?php

namespace Mpietrucha\Regexp\Provider;

use Mpietrucha\Regexp\System;
use Mpietrucha\Regexp\Contracts\ProviderInterface;

class UrlsProvider implements ProviderInterface
{
    protected const REGEXP = '#\bhttps?://[^,\s()<>]+(?:\([\w\d]+\)|([^,[:punct:]\s]|/))#';

    public function name(): string
    {
        return 'urls';
    }

    public function handle(string $source): array
    {
        return System\Matcher::all(self::REGEXP, $source);
    }
}
