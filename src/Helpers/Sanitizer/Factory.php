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

namespace Maslosoft\Mangan\Helpers\Sanitizer;

use Maslosoft\Addendum\Collections\MetaProperty;
use Maslosoft\Mangan\Sanitizers\Boolean;
use Maslosoft\Mangan\Sanitizers\Double;
use Maslosoft\Mangan\Sanitizers\Integer;
use Maslosoft\Mangan\Sanitizers\PassThrough;
use Maslosoft\Mangan\Sanitizers\String;

/**
 * Factory
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class Factory
{

	public static function create(MetaProperty $meta)
	{
		if ($meta->sanitizer)
		{
			if (strstr($meta->sanitizer, '\\') === false)
			{
				$className = sprintf('%s\%s', PassThrough::Ns, $meta->sanitizer);
			}
			else
			{
				$className = $meta->sanitizer;
			}
			return new $className;
		}

		switch (gettype($meta->default))
		{
			case 'boolean':
				return new Boolean;
			case 'double':
				return new Double;
			case 'integer':
				return new Integer;
			case 'string':
				return new String;
		}
		return new PassThrough();
	}

}
