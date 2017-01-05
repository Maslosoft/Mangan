<?php

/**
 * This software package is licensed under AGPL or Commercial license.
 *
 * @package maslosoft/mangan
 * @licence AGPL or Commercial
 * @copyright Copyright (c) Piotr Masełkowski <pmaselkowski@gmail.com>
 * @copyright Copyright (c) Maslosoft
 * @copyright Copyright (c) Others as mentioned in code
 * @link https://maslosoft.com/mangan/
 */

namespace Maslosoft\Mangan\Annotations;

use Maslosoft\Addendum\Helpers\ParamsExpander;
use Maslosoft\Mangan\Decorators\EmbeddedDecorator;
use Maslosoft\Mangan\Meta\EmbeddedMeta;
use Maslosoft\Mangan\Meta\ManganPropertyAnnotation;

/**
 * Annotation for embedded document in mongo
 * `defaultClassName` will be used for getting empty properties,
 * but any type of embedded document can be stored within this field
 * Examples:
 *
 * Embed with namespaced class literal:
 *
 * ```
 * @Embedded(Company\Product\EmbeddedClassName)
 * ```
 * 
 * Embed with default class - short notation - `EmbeddedClassName` imported via
 * use statement:
 *
 * ```
 * @Embedded(EmbeddedClassName)
 * ```
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
		$this->getEntity()->embedded = $meta;
		$this->getEntity()->propagateEvents = true;
		$this->getEntity()->owned = true;
		$this->getEntity()->decorators[] = EmbeddedDecorator::class;
	}

}
