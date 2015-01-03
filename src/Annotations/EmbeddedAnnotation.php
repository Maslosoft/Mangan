<?php

namespace Maslosoft\Mangan\Annotations;

use Maslosoft\Mangan\Decorators\EmbeddedDecorator;
use Maslosoft\Mangan\Meta\ManganAnnotation;
use Maslosoft\Mangan\Sanitizers\Embedded;
use stdClass;

/**
 * Annotation for embedded document in mongo
 * defaultClassName will be used for getting empty properties,
 * but any type of embedded document can be stored within this field
 * Examples:
 * <ul>
 * 		<li><b>Embedded(Company\Product\EmbeddedClassName)</b>: Embed with namespaced class literal</li>
 * 		<li><b>Embedded(EmbeddedClassName)</b>: Embed with default class</li>
 * </ul>
 * @Target('property')
 * @template Embedded(${defaultClassName})
 * @author Piotr
 */
class EmbeddedAnnotation extends ManganAnnotation
{

	public $value = true;

	public function init()
	{
		$this->_entity->embedded = $this->value;
		$this->_entity->sanitizer = Embedded::class;
		$this->_entity->decorators[] = EmbeddedDecorator::class;
	}

}
