<?php

namespace Maslosoft\Mangan\Annotations;

use Maslosoft\Addendum\Collections\MetaAnnotation;
use Maslosoft\Mangan\Sanitizers\EmbeddedArray;

/**
 * Annotation for array of embedded documents in mongo
 * defaultClassName will be used for getting empty properties,
 * but any type of embedded document can be stored within this field
 * @Target('property')
 * @template EmbeddedArray('${defaultClassName}')
 * @author Piotr
 */
class EmbeddedArrayAnnotation extends MetaAnnotation
{

	public $value = true;

	public function init()
	{
		$this->_entity->embedded = true;
		$this->_entity->embeddedArray = true;
		$this->_entity->direct = false;
		$this->_entity->sanitizer = EmbeddedArray::class;
	}

}
