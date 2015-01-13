<?php

/**
 * This software package is licensed under New BSD license.
 *
 * @package maslosoft/mangan
 * @licence New BSD
 * @copyright Copyright (c) Piotr Masełkowski <pmaselkowski@gmail.com>
 * @copyright Copyright (c) Maslosoft
 * @copyright Copyright (c) Others as mentioned in code
 * @link http://maslosoft.com/mangan/
 */

namespace Maslosoft\Mangan\Helpers\Decorator;

use Maslosoft\Mangan\Decorators\Undecorated;
use Maslosoft\Mangan\Meta\DocumentPropertyMeta;

/**
 * Factory
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class Factory
{

	public static function create(DocumentPropertyMeta $meta)
	{
		if ($meta->decorators)
		{
			$decorators = [];
			foreach($meta->decorators as $decoratorName)
			{
				$decorators[] = new $decoratorName;
			}
			return new CompoundDecorator($decorators);
		}
		return new Undecorated();
	}

}
