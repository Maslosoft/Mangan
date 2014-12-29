<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Maslosoft\Mangan\Helpers;

use Maslosoft\Mangan\Interfaces\IWithCollectionName;
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
		if ($model instanceof IWithCollectionName)
		{
			return $model->getCollectionName();
		}
		$meta = ManganMeta::create($model)->type();
		$name = $meta->collectionName;
		if($name)
		{
			return $name;
		}
		return ltrim('\\', str_replace('\\', '.', $meta->name));
	}

}
