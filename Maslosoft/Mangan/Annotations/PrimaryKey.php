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
 * TODO
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class PrimaryKey extends MetaAnnotation
{

	public $value = '_id';

	public function init()
	{
		$this->_entity->primaryKey = $this->value;
	}

}
