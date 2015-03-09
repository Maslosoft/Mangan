<?php

/**
 * This software package is licensed under New BSD license.
 *
 * @package maslosoft/mangan
 * @licence New BSD
 * @copyright Copyright (c) Piotr Masełkowski <pmaselkowski@gmail.com>
 * @copyright Copyright (c) Maslosoft
 * @copyright Copyright (c) Others as mentioned in code
 * @link http://maslosoft.com/mangan/
 */

namespace Maslosoft\Mangan\Annotations;

use Maslosoft\Mangan\Meta\DocumentTypeMeta;
use Maslosoft\Mangan\Meta\ManganPropertyAnnotation;

/**
 * PrimaryKey
 * TODO Make it possible to configure compound primary key. This should be made by using annotation on multiple fields
 * FIXME This currently set *field* meta, while it should set *type* meta
 * @Target('property')
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class PrimaryKeyAnnotation extends ManganPropertyAnnotation
{

	public function init()
	{
		// Set primaryKey value on type
		$type = $this->_meta->type();
		/* @var $type DocumentTypeMeta */
		$name = $this->_entity->name;
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
