<?php

/**
 * This software package is licensed under AGPL or Commercial license.
 *
 * @package maslosoft/mangan
 * @licence AGPL or Commercial
 * @copyright Copyright (c) Piotr Masełkowski <pmaselkowski@gmail.com>
 * @copyright Copyright (c) Maslosoft
 * @copyright Copyright (c) Others as mentioned in code
 * @link http://maslosoft.com/mangan/
 */

namespace Maslosoft\Mangan\Annotations;

use Maslosoft\Mangan\Decorators\EmbeddedArrayDecorator;
use Maslosoft\Mangan\Meta\ManganPropertyAnnotation;

/**
 * Annotation for array of embedded documents in mongo
 * defaultClassName will be used for getting empty properties,
 * but any type of embedded document can be stored within this field
 * @Target('property')
 * @template EmbeddedArray('${defaultClassName}')
 * @author Piotr
 */
class EmbeddedArrayAnnotation extends ManganPropertyAnnotation
{

	public $value = true;

	public function init()
	{
		$this->_entity->embedded = $this->value;
		$this->_entity->propagateEvents = true;
		$this->_entity->decorators[] = EmbeddedArrayDecorator::class;
	}

}
