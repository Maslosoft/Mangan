<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Maslosoft\Mangan\Annotations;

use Maslosoft\Addendum\Collections\MetaAnnotation;

/**
 * PrimaryKey
 * TODO Make it possible to configure compound primary key. This should be made by using annotation on multiple fields
 * FIXME This currently set *field* meta, while it should set *type* meta
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class PrimaryKey extends MetaAnnotation
{

	public function init()
	{
		// Set primaryKey value on type
		$type = $this->_meta->type();
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
