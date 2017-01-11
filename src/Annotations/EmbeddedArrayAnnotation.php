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
use Maslosoft\Mangan\Decorators\EmbeddedArrayDecorator;
use Maslosoft\Mangan\Meta\EmbeddedMeta;

/**
 * Annotation for array of embedded documents in MongoDB
 * default class name will be used for getting empty properties,
 * but any type of embedded document can be stored within this field.
 *
 * Examples:
 *
 * Embedded array with any model:
 * ```
 * @EmbeddedArray
 * ```
 *
 * Embedded array with default class:
 * ```
 * @EmbeddedArray(Company\ClassName)
 * ```
 *
 * Embedded array with default class and compare key `_id`
 * ```
 * @EmbeddedArray(Company\ClassName, '_id')
 * ```
 *
 * Embedded array with default class and composite compare key of `login` and `email`:
 * ```
 * @EmbeddedArray(Company\ClassName, {'login', 'email'})
 * ```
 *
 * @Target('property')
 *
 * @Conflicts('Embedded')
 * @Conflicts('DbRef')
 * @Conflicts('DbRefArray')
 * @Conflicts('Related')
 * @Conflicts('RelatedArray')
 *
 *
 * @template EmbeddedArray('${defaultClassName}')
 * @author Piotr
 */
class EmbeddedArrayAnnotation extends EmbeddedAnnotation
{

	public $value = true;

	/**
	 * Comparing key. This is used to update db ref instances from external sources.
	 * @var string|array
	 */
	public $key = null;

	public function init()
	{
		$data = ParamsExpander::expand($this, ['class', 'key']);
		$meta = new EmbeddedMeta($data);
		$meta->isArray = true;
		$this->getEntity()->embedded = $meta;
		$this->getEntity()->propagateEvents = true;
		$this->getEntity()->owned = true;
		$this->getEntity()->decorators[] = EmbeddedArrayDecorator::class;
	}

}
