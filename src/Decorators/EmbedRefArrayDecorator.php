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

namespace Maslosoft\Mangan\Decorators;

use Maslosoft\Mangan\Meta\DocumentPropertyMeta;
use Maslosoft\Mangan\Meta\ManganMeta;

/**
 * Embed Ref Array Decorator is alias for embedded array decorator
 * for converting arrays of Db Refs into JSON arrays, Document arrays etc.
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class EmbedRefArrayDecorator extends EmbeddedArrayDecorator
{

	protected static function getClassName($model, $name)
	{
		$fieldMeta = ManganMeta::create($model)->$name;

		/* @var $fieldMeta DocumentPropertyMeta */
		return $fieldMeta->dbRef->class;
	}

}
