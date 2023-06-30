<?php

namespace Mpietrucha\Regexp;

use Illuminate\Support\Collection;
use Mpietrucha\Finder\InstanceFinder;
use Mpietrucha\Support\Concerns\HasFactory;
use Mpietrucha\Exception\InvalidArgumentException;
use Mpietrucha\Regexp\Contracts\ProviderInterface;

class Regexp
{
    use HasFactory;

    protected ?ProviderInterface $provider = null;

    protected static ?Collection $providers = null;

    public function __construct(protected ?string $name = null, protected ?string $source = null)
    {
    }

    public static function __callStatic(string $method, array $arguments): Collection
    {
        return self::create($method, ...$arguments)->collect();
    }

    public static function providers(): Collection
    {
        return self::$providers ??= InstanceFinder::create(__DIR__.'/Provider')->instance(function (string $namespace) {
            return class_implements_interface($namespace, ProviderInterface::class);
        })->instances()->mapWithKeys(fn (ProviderInterface $provider) => [
            $provider->name() => $provider
        ]);
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

    public function toArray(): array
    {
        return $this->collect()->toArray();
    }

    public function collect(): Collection
    {
        throw_unless($this->name && $this->source, new InvalidArgumentException(
            'Provide valid name and source'
        ));

        if ($this->provider?->name() !== $this->name) {
            $this->provider = self::providers()->get($this->name);
        }

        throw_unless($this->provider, new InvalidArgumentException(
            'Cannot find any provider for', [$this->name]
        ));

        $response = $this->provider->handle($this->source);

        return collect($response)->values()->filter()->unique();
    }
}
