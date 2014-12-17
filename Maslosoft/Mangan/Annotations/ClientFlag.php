<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Maslosoft\Mangan\Annotations;

use Maslosoft\Addendum\Collections\MetaAnnotation;
use Maslosoft\Mangan\Helpers\PropertyMaker;

/**
 * ClientFlag
 * Set client flag
 * Example: ClientFlag(w = 0, fsync = false)
 * @see \Maslosoft\Mangan\Traits\Defaults\MongoClientOptions
 * @template ClientFlag(${flags})
 * @Target('class')
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class ClientFlag extends MetaAnnotation
{

	use \Maslosoft\Mangan\Traits\Defaults\MongoClientOptions;

	public $value = [];

	public function init()
	{
		foreach ($this->_properties as $name => $value)
		{
			$this->_entity->$name = $value;
		}
	}

}
