<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Maslosoft\Mangan\Annotations;

use Maslosoft\Addendum\Collections\MetaAnnotation;

/**
 * HomogenousAnnotation
 * Default to true, set this to false to allow stoging arbitrary models types in collection
 * @template Homogenous(${isHomogenous})
 * @Target('class')
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class HomogenousAnnotation extends MetaAnnotation
{

	public $value = true;

	public function init()
	{
		$this->_entity->homogenous = $this->value;
	}

}
