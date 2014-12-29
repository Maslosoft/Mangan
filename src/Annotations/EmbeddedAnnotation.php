<?php

namespace Maslosoft\Mangan\Annotations;

use Maslosoft\Addendum\Collections\MetaAnnotation;
use Maslosoft\Mangan\Decorators\EmbeddedDecorator;
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
 * 		<li><b>Embedded({'EmbeddedClassName', params...})</b>: Embed with default class and optional params (currently none)</li>
 * </ul>
 * @Target('property')
 * @template Embedded(${defaultClassName})
 * @author Piotr
 */
class EmbeddedAnnotation extends MetaAnnotation
{

	public $value = true;

	public function init()
	{
		$params = new stdClass();
		if (is_array($this->value))
		{
			$className = array_shift($this->value);
			$params = (object) $this->value;
		}
		else
		{
			$className = $this->value;
		}
		$this->_entity->embedded = $className;
		$this->_entity->embeddedParams = $params;
		$this->_entity->direct = false;
		$this->_entity->sanitizer = Embedded::class;
		$this->_entity->decorators[] = EmbeddedDecorator::class;
	}

}
