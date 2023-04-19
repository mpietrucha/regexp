<?php

namespace Mpietrucha\Regexp;

use Exception;
use Mpietrucha\Finder\InstancesFinder;
use Illuminate\Support\Collection;
use Mpietrucha\Support\Concerns\HasFactory;
use Mpietrucha\Regexp\Contracts\ProviderInterface;

class Regexp
{
    use HasFactory;

    protected ?ProviderInterface $provider = null;

    protected static ?Collection $providers = null;

    public function __construct(protected ?string $name = null, protected ?string $source = null)
    {
    }

    public static function providers(): Collection
    {
        return self::$providers ??= InstancesFinder::create(__DIR__.'/Provider')->instances(
            fn (Collection $providers) => $providers->filter(fn (string $provider) => class_implements_interface($provider, ProviderInterface::class))
        )->mapWithKeys(fn (ProviderInterface $provider) => [$provider->name() => $provider]);
    }

    public function name(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function source(string $source): self
    {
        $this->source = $source;

        return $this;
    }

    public function collect(): Collection
    {
        if (! $this->name || ! $this->source) {
            throw new Exception('Cannot fetch regexp from invalid source/name');
        }

        if (! $this->provider?->name() !== $this->name) {
            $this->provider = self::providers()->get($this->name);
        }

        if (! $this->provider) {
            throw new Exception("Cannot find any provider for name `$name`");
        }

        return collect($this->provider->handle($this->source))->values()->filter()->unique();
    }

    public function toArray(): array
    {
        return $this->collect()->toArray();
    }
}
