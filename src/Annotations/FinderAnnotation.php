<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Maslosoft\Mangan\Annotations;

use Maslosoft\Mangan\Meta\ManganTypeAnnotation;

/**
 * FinderAnnotation
 * 
 * @template Finder(${finderClassLiteral})
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class FinderAnnotation extends ManganTypeAnnotation
{

	public $value = null;

	public function init()
	{
		$this->_entity->finder = $this->value;
	}

}
