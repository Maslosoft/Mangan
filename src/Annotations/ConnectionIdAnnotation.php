<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Maslosoft\Mangan\Annotations;

use Maslosoft\Mangan\Meta\ManganTypeAnnotation;

/**
 * ConnectionIdAnnotation
 * @template ConnectionId('${connectionId}')
 * @Target('class')
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class ConnectionIdAnnotation extends ManganTypeAnnotation
{

	public $value = null;

	public function init()
	{
		$this->_entity->connectionId = $this->value;
	}

}
