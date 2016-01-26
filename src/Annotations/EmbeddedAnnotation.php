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

use Maslosoft\Addendum\Helpers\ParamsExpander;
use Maslosoft\Mangan\Decorators\EmbeddedDecorator;
use Maslosoft\Mangan\Meta\EmbeddedMeta;
use Maslosoft\Mangan\Meta\ManganPropertyAnnotation;

/**
 * Annotation for embedded document in mongo
 * defaultClassName will be used for getting empty properties,
 * but any type of embedded document can be stored within this field
 * Examples:
 * <ul>
 * 		<li><b>Embedded(Company\Product\EmbeddedClassName)</b>: Embed with namespaced class literal</li>
 * 		<li><b>Embedded(EmbeddedClassName)</b>: Embed with default class</li>
 * </ul>
 *
 * @Conflicts('EmbeddedArray')
 * @Conflicts('DbRef')
 * @Conflicts('DbRefArray')
 * @Conflicts('Related')
 * @Conflicts('RelatedArray')
 *
 * @Target('property')
 * @template Embedded(${defaultClassName})
 * @author Piotr
 */
class EmbeddedAnnotation extends ManganPropertyAnnotation
{

	public $value = true;

	public function init()
	{
		$data = ParamsExpander::expand($this, ['class']);
		$meta = new EmbeddedMeta($data);
		$meta->single = true;
		$this->_entity->embedded = $meta;
		$this->_entity->propagateEvents = true;
		$this->_entity->owned = true;
		$this->_entity->decorators[] = EmbeddedDecorator::class;
	}

}
