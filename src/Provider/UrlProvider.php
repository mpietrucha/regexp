<?php

namespace Mpietrucha\Regexp\Provider;

use Mpietrucha\Regexp\Contracts\ProviderInterface;

class UrlProvider implements ProviderInterface
{
    protected const REGEXP = '#\bhttps?://[^,\s()<>]+(?:\([\w\d]+\)|([^,[:punct:]\s]|/))#';

    public function name(): string
    {
        return 'url';
    }

    public function handle(string $source): array
    {
        preg_match_all(self::REGEXP, $source, $matches);

        return head($matches) ?? [];
    }
}
