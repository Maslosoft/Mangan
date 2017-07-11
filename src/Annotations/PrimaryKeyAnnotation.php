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

namespace Maslosoft\Mangan\Annotations;

use Maslosoft\Mangan\Meta\DocumentTypeMeta;
use Maslosoft\Mangan\Meta\ManganPropertyAnnotation;

/**
 * To define simple primary key mark field with `PrimaryKey` annotation.
 * To define composite primary key, use this annotation on several fields.
 * 
 * @Target('property')
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class PrimaryKeyAnnotation extends ManganPropertyAnnotation
{

	public function init()
	{
		// Set primaryKey value on type
		$type = $this->getMeta()->type();
		/* @var $type DocumentTypeMeta */
		$name = $this->getEntity()->name;
		// If key is already defined it means that it is composite
		if ($type->primaryKey)
		{
			// If it is array then add field
			if (is_array($type->primaryKey))
			{
				$type->primaryKey[] = $name;
			}
			else
			{
				// it is not array so create composite from existing
				// field and add new field to definition
				$type->primaryKey = [$type->primaryKey, $name];
			}
		}
		else
		{
			// Simple primary key
			$type->primaryKey = $name;
		}
	}

}
