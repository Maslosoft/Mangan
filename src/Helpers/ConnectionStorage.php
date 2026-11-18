<?php

declare(strict_types=1);

/**
 * This software package is licensed under AGPL or Commercial license.
 *
 * @package maslosoft/mangan
 * @licence AGPL or Commercial
 * @copyright Copyright (c) Piotr Masełkowski <pmaselkowski@gmail.com>
 * @copyright Copyright (c) Maslosoft
 * @copyright Copyright (c) Others as mentioned in code
 * @link https://maslosoft.com/mangan/
 */

namespace Maslosoft\Mangan\Helpers;

use MongoDB\Client;
use MongoDB\Database;

/** Connection handles shared by connection ID in the current Mangan context. @internal */
final class ConnectionStorage
{
    private string $key;

    public function __construct(object|string $owner, string $instanceId)
    {
        $this->key = serialize([is_object($owner) ? $owner::class : $owner, $instanceId]);
    }

    private function values(): object
    {
        $cache = &RuntimeState::cache(self::class);
        return $cache[$this->key] ??= new class {
            public ?Client $mongoClient = null;
            public ?Database $mongoDB = null;
        };
    }

    public function __get(string $name): Client|Database|null
    {
        return $this->values()->$name;
    }

    public function __set(string $name, Client|Database|null $value): void
    {
        if (!in_array($name, ['mongoClient', 'mongoDB'], true)) {
            throw new \InvalidArgumentException('Unknown connection storage property: ' . $name);
        }
        $this->values()->$name = $value;
    }
}
