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

namespace Maslosoft\Mangan\Helpers;

use Maslosoft\Addendum\Addendum;
use Maslosoft\Addendum\Interfaces\AnnotatedInterface;
use Maslosoft\Cli\Shared\Helpers\PhpExporter;
use Maslosoft\Mangan\Helpers\Index\IndexModel;
use Maslosoft\Mangan\Interfaces\InternationalInterface;
use Maslosoft\Mangan\Mangan;
use Maslosoft\Mangan\Meta\ManganMeta;
use RuntimeException;

class IndexManager
{
    public const IndexTypeHashed = 'hashed';
    public const IndexType2dSphere = '2dsphere';
    public const DefaultInstanceId = 'indexManager';

    private array $haveIndex = [];

    public static function fly($instanceId = self::DefaultInstanceId): static
    {
        return RuntimeState::current()->indexManagers[static::class][$instanceId] ??= new static();
    }

    /** Clear this manager's in-memory results; disk markers are managed separately. */
    public function reset(): void
    {
        $this->haveIndex = [];
    }

    public function create(AnnotatedInterface $model): bool
    {
        $path = $this->getStoragePath($model, $model::class);
        if (array_key_exists($path, $this->haveIndex)) {
            return $this->haveIndex[$path];
        }
        $fields = array_filter(ManganMeta::create($model)->fields(), static fn($field) => !empty($field->index));
        if ($fields === []) {
            return $this->haveIndex[$path] = false;
        }
        if (is_file($path)) {
            return $this->haveIndex[$path] = true;
        }

        $indexes = [];
        foreach ($fields as $field) {
            foreach ($field->index as $meta) {
                $index = new IndexModel($model, $meta);
                // Errors must remain visible and retryable: never cache failed creation.
                if (!$index->apply()) {
                    return false;
                }
                $indexes[] = $index->getIndexes();
            }
        }

        $dir = dirname($path);
        if (!is_dir($dir) && !@mkdir($dir, 0775, true) && !is_dir($dir)) {
            throw new RuntimeException('Cannot create index cache directory: ' . $dir);
        }
        // Multiple workers may issue the same idempotent index operation. Publish a
        // complete marker atomically only after every index operation succeeded.
        $temporary = tempnam($dir, '.index-');
        if ($temporary === false) {
            throw new RuntimeException('Cannot create index cache file in: ' . $dir);
        }
        try {
            if (file_put_contents($temporary, PhpExporter::export($indexes, 'Auto generated, do not modify')) === false
                || !rename($temporary, $path)) {
                throw new RuntimeException('Cannot write index cache: ' . $path);
            }
        } finally {
            if (is_file($temporary)) {
                unlink($temporary);
            }
        }
        return $this->haveIndex[$path] = true;
    }

    public function getStoragePath(?AnnotatedInterface $model = null, $className = null): string
    {
        $mangan = $model === null ? Mangan::fly() : Mangan::fromModel($model);
        // Database selection and languages may change even inside one context.
        $definitions = [];
        if ($model !== null) {
            foreach (ManganMeta::create($model)->fields() as $name => $field) {
                if (!empty($field->index)) {
                    $definitions[$name] = $field->index;
                }
            }
        }
        $identity = [
            (string)$mangan->getConnection(),
            $mangan->connectionId,
            $mangan->dbName,
            $model === null ? null : CollectionNamer::nameCollection($model),
            $className ?? ($model === null ? self::class : $model::class),
            $model instanceof InternationalInterface ? $model->getLanguages() : [],
            $definitions,
        ];
        return sprintf('%s/%s/%s.php', rtrim(Addendum::fly()->runtimePath, '/'),
            str_replace('\\', '.', static::class), hash('sha256', serialize($identity)));
    }
}
