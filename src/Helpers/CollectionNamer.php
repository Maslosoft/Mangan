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

use Maslosoft\Mangan\Interfaces\CollectionNameInterface;
use Maslosoft\Mangan\Meta\ManganMeta;

/**
 * CollectionNamer
 * Name collection from model instance. Order of name resolving:
 * <ul>
 * <li>`CollectionName` annotation</li>
 * <li>Model method `getCollectionName`</li>
 * <li>Fully qualified class name with `\` replaced with `.`</li>
 * </ul>
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class CollectionNamer
{

	public static function nameCollection($model)
	{
		if ($model instanceof CollectionNameInterface)
		{
			return $model->getCollectionName();
		}
		$meta = ManganMeta::create($model)->type();
		$name = $meta->collectionName;
		if($name)
		{
			return $name;
		}
		$name = get_class($model);
		return ltrim(str_replace('\\', '.', $name), '\\');
	}

}
