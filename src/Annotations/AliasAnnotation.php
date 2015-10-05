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

use Maslosoft\Mangan\Meta\DocumentTypeMeta;
use Maslosoft\Mangan\Meta\ManganTypeAnnotation;

/**
 * Make alias to field. If any of two fields will not be default, both field will be set.
 * Example, alias to `_id` on field `id`:
 *
 * 		&commat;Alias('_id')
 *
 * @template Alias('${fieldName}')
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class AliasAnnotation extends ManganTypeAnnotation
{

	public $value = null;

	public function init()
	{
		// Set aliases value on type
		$type = $this->_meta->type();
		/* @var $type DocumentTypeMeta */
		$name = $this->_entity->name;
		$type->aliases[$name] = $this->value;
	}

}
