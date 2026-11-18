<?php

declare(strict_types=1);

namespace Maslosoft\Mangan\Helpers;

use Maslosoft\Light\Context;

/** Mutable Mangan registries belonging to the current Light context. @internal */
final class RuntimeState
{
    private const string Key = 'maslosoft.mangan.runtime';

    public array $instances = [];
    public array $classToId = [];
    public array $events = [];
    public array $propagated = [];
    public array $sessions = [];
    public array $sessionConnections = [];
    public array $caches = [];
    public array $indexManagers = [];
    public bool $legacyResolverInitialized = false;
    public bool $handlersInitialized = false;

    public static function current(): self
    {
        if (!Context::has(self::Key)) {
            Context::put(self::Key, new self());
        }
        return Context::get(self::Key);
    }

    /** Does not reset configuration owned by EmbeDi, Addendum, or Signals. */
    public static function reset(): void
    {
        Context::delete(self::Key);
    }

    public static function &cache(string $owner): array
    {
        $state = self::current();
        $state->caches[$owner] ??= [];
        return $state->caches[$owner];
    }
}
