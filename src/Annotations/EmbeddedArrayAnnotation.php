<?php

namespace Maslosoft\Mangan\Annotations;

use Maslosoft\Mangan\Decorators\EmbeddedArrayDecorator;
use Maslosoft\Mangan\Meta\ManganAnnotation;
use Maslosoft\Mangan\Sanitizers\EmbeddedArray;

/**
 * Annotation for array of embedded documents in mongo
 * defaultClassName will be used for getting empty properties,
 * but any type of embedded document can be stored within this field
 * @Target('property')
 * @template EmbeddedArray('${defaultClassName}')
 * @author Piotr
 */
class EmbeddedArrayAnnotation extends ManganAnnotation
{

	public $value = true;

	public function init()
	{
		$this->_entity->embedded = $this->value;
		$this->_entity->sanitizer = EmbeddedArray::class;
		$this->_entity->decorators[] = EmbeddedArrayDecorator::class;
	}

}
