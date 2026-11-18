<?php

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

namespace Maslosoft\Mangan\Traits\Defaults;

use ReflectionClass;
use ReflectionProperty;

/** MongoDB URI options. Null leaves the URI and driver default unchanged. */
trait MongoClientOptions
{
    public $authMechanism = null;
    public $authSource = null;
    public $connectTimeoutMS = null;
    public $journal = null;
    public $password = null;
    public $readPreference = null;
    public $readPreferenceTags = null;
    public $replicaSet = null;
    public $socketTimeoutMS = null;
    public $ssl = null;
    public $username = null;
    public $w = null;
    public $wTimeoutMS = null;
    public $serverSelectionTimeoutMS = null;
    public $appname = null;

    protected function _getOptionNames(): array
    {
        return array_map(
            static fn(ReflectionProperty $property): string => $property->name,
            (new ReflectionClass(MongoClientOptions::class))->getProperties(ReflectionProperty::IS_PUBLIC)
        );
    }
}
