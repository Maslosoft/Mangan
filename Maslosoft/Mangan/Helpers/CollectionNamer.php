<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Maslosoft\Mangan\Helpers;

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
		if ($model->meta->collectionName)
		{
			return $model->meta->collectionName;
		}
		if (is_callable([$model, 'getCollectionName']))
		{
			return $model->getCollectionName();
		}
		return str_replace('\\', '.', get_class($model));
	}

}
