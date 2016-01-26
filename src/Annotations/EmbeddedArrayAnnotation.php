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
use Maslosoft\Mangan\Decorators\EmbeddedArrayDecorator;
use Maslosoft\Mangan\Meta\EmbeddedMeta;

/**
 * Annotation for array of embedded documents in mongo
 * defaultClassName will be used for getting empty properties,
 * but any type of embedded document can be stored within this field.
 * By default, when updating from external source, documents are compared by pk.
 * This can be ovverriden with `key` property.
 * Examples:
 * <ul>
 * 		<li>&commat;EmbeddedArray() - Embedded array with any model</li>
 * 		<li>&commat;EmbeddedArray(Company\ClassName) - Embedded array with default class</li>
 * 		<li>&commat;EmbeddedArray(Company\ClassName, '_id') - Embedded array with default class and compare key `_id`</li>
 * 	<li>&commat;EmbeddedArray(Company\ClassName, {'login', 'email'}) - Embedded array with default class and composite compare key `_id`</li>
 * </ul>
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
		$this->_entity->embedded = $meta;
		$this->_entity->propagateEvents = true;
		$this->_entity->owned = true;
		$this->_entity->decorators[] = EmbeddedArrayDecorator::class;
	}

}
