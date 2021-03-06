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

use function dirname;
use function file_exists;
use Maslosoft\Addendum\Addendum;
use Maslosoft\Addendum\Helpers\SoftIncluder;
use Maslosoft\Addendum\Interfaces\AnnotatedInterface;
use Maslosoft\Cli\Shared\Helpers\PhpExporter;
use Maslosoft\Mangan\Helpers\Index\IndexModel;
use Maslosoft\Mangan\Mangan;
use Maslosoft\Mangan\Meta\DocumentPropertyMeta;
use Maslosoft\Mangan\Meta\ManganMeta;
use Maslosoft\ManganTest\Extensions\IndexMetaCleaner;
use function mkdir;

class IndexManager
{
	const IndexTypeHashed = 'hashed';
	const IndexType2dSphere = '2dsphere';

	const DefaultInstanceId = 'indexManager';

	private static $instances = [];

	private static $paths = [];

	/**
	 * NOTE: This is public because of IndexMetaCleaner testing extension
	 *
	 * DO NOT TOUCH!
	 *
	 * @see IndexMetaCleaner
	 * @internal
	 * @var array
	 */
	public static $haveIndex = [];

	/**
	 * NOTE: This is public because of IndexMetaCleaner testing extension
	 *
	 * DO NOT TOUCH!
	 *
	 * @see IndexMetaCleaner
	 * @internal
	 * @var bool
	 */
	public static $haveDir = false;

	/**
	 * Create flyweight instance of index manager
	 * @param string $instanceId
	 * @return static
	 */
	public static function fly($instanceId = self::DefaultInstanceId)
	{
		if (empty(self::$instances[$instanceId]))
		{
			self::$instances[$instanceId] = new static();
		}
		return self::$instances[$instanceId];
	}

	public function create(AnnotatedInterface $model)
	{
		$className = get_class($model);

		// If have or don't have indexes skip further checks
		if(array_key_exists($className, self::$haveIndex))
		{
			return self::$haveIndex[$className];
		}

		$fieldMetas = ManganMeta::create($model)->fields();

		// Filter out fields without index
		foreach($fieldMetas as $key => $metaProperty)
		{
			if(empty($metaProperty->index))
			{
				unset($fieldMetas[$key]);
			}
		}

		// Does not have indexes, mark as index-less
		if(empty($fieldMetas))
		{
			self::$haveIndex[$className] = false;
			return false;
		}

		$path = $this->getStoragePath($model, $className);

		$data = SoftIncluder::includeFile($path);

		if(!empty($data))
		{
			return true;
		}
		$results = [];
		$indexes = [];
		foreach($fieldMetas as $fieldMeta)
		{
			if(empty($fieldMeta->index))
			{
				continue;
			}
			/* @var $fieldMeta DocumentPropertyMeta */

			foreach($fieldMeta->index as $indexMeta)
			{
				$index = new IndexModel($model, $indexMeta);
				$results[] = (int)$index->apply();
				$indexes[] = $index->getIndexes();
			}
		}
		self::$haveIndex[$className] = true;

		$dir = dirname($path);

		if(!self::$haveDir && !file_exists($dir))
		{
			$mask = umask(0000);
			$parentDir = dirname($dir);
			if(!file_exists($parentDir))
			{
				mkdir($parentDir, 0777);
			}
			self::$haveDir = mkdir($dir, 0777);
			umask($mask);
		}

		file_put_contents($path, PhpExporter::export($indexes, 'Auto generated, do not modify'));
		@chmod($path, 0666);

		return array_sum($results) === count($results);
	}

	public function getStoragePath(AnnotatedInterface $model = null, $className = null)
	{
		if(empty($className))
		{
			$className = __CLASS__;
		}
		if(empty(self::$paths[$className]))
		{
			if(empty($model))
			{
				$mn = Mangan::fly();
			}
			else
			{
				$mn = Mangan::fromModel($model);
			}

			$params = [
				Addendum::fly()->runtimePath,
				str_replace('\\', '.', static::class),
				$mn->connectionId,
				$mn->dbName,
				str_replace('\\', '.', $className),
];
			self::$paths[$className] = vsprintf('%s/%s/%s.%s@%s.php', $params);
		}
		return self::$paths[$className];
	}


}