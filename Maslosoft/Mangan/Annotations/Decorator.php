<?php

/**
 * @licence For licence @see LICENCE.html
 *
 * @copyright Maslosoft
 * @link http://maslosoft.com/
 */

namespace Maslosoft\Mangan\Annotations;

use Maslosoft\Addendum\Collections\MetaAnnotation;

/**
 * Decorator. There might be many decorators
 * @template Decorator(${DecoratorClass})
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class Decorator extends MetaAnnotation
{

	public $value = null;

	public function init()
	{
		$this->_entity->decorators[] = $this->value;
	}

}