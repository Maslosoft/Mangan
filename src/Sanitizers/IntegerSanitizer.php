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

namespace Maslosoft\Mangan\Sanitizers;

use Maslosoft\Mangan\Interfaces\Sanitizers\Property\ISanitizer;

/**
 * Integer
 * This sanitizer forces type to be integer
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class IntegerSanitizer implements ISanitizer
{

	public function read($model, $dbValue)
	{
		return (int) $dbValue;
	}

	public function write($model, $phpValue)
	{
		return (int) $phpValue;
	}

}