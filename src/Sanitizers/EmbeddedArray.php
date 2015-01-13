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
 * EmbeddedArray
 * @deprecated since version number
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class EmbeddedArray implements ISanitizer
{

	public function read($model, $dbValue)
	{
		/**
		 * TODO Instantiate embedded array
		 */
		return $dbValue;
	}

	public function write($model, $phpValue)
	{
		/**
		 * TODO Convert embedded array into plain php array
		 */
		return $phpValue;
	}

}
