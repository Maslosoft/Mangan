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

namespace Maslosoft\Mangan\Sanitizers;

/**
 * Float
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class Double implements ISanitizer
{

	public function read($model, $dbValue)
	{
		return (float) $dbValue;
	}

	public function write($model, $phpValue)
	{
		return (float) $phpValue;
	}

}
