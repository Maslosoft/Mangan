<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Maslosoft\Mangan\Annotations;

use Maslosoft\Mangan\Meta\ManganPropertyAnnotation;

/**
 * Sanitizer. There cen be only one sanitizer
 * @template SanitizerArray(${SanitizerClass})
 * @Target('property')
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class SanitizerArrayAnnotation extends ManganPropertyAnnotation
{

	public $value = null;

	public function init()
	{
		$this->_entity->sanitizer = $this->value;
		$this->_entity->sanitizeArray = true;
	}

}
