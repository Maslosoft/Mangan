<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Maslosoft\Mangan\Transformers;

use Maslosoft\Mangan\Exceptions\TransformatorException;
use Maslosoft\Mangan\Helpers\Decorator\Decorator;
use Maslosoft\Mangan\Meta\ManganMeta;

/**
 * FromRawArray
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class FromRawArray
{

	/**
	 * Create document from array
	 * @return object
	 */
	public static function toDocument($data, $className = null)
	{
		if(!$data)
		{
			return null;
		}
		if(is_object($className))
		{
			$className = get_class($className);
		}
		if (!$className)
		{
			if (array_key_exists('_class', $data))
			{
				$className = $data['_class'];
			}
			else
			{
				throw new TransformatorException('Could not determine document type');
			}
		}
		return self::_toDocument($className, $data);
	}

	private static function _toDocument($className, $data)
	{
		$document = new $className;
		$meta = ManganMeta::create($document);
		$decorator = new Decorator($document);
		foreach ($data as $field => $data)
		{
			$fieldMeta = $meta->$field;
			/* @var \Maslosoft\Mangan\Meta\DocumentPropertyMeta $fieldMeta */
			if (!$fieldMeta)
			{
				continue;
			}
			$decorator->read($field, $data);
		}
		return $document;
	}

}
