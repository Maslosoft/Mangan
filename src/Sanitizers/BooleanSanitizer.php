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

namespace Maslosoft\Mangan\Sanitizers;

use Maslosoft\Mangan\Interfaces\Sanitizers\Property\SanitizerInterface;

/**
 * Bool
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class BooleanSanitizer implements SanitizerInterface
{

	public function read($model, $dbValue)
	{
		return (bool) $dbValue;
	}

	public function write($model, $phpValue)
	{
		return (bool) $phpValue;
	}

}
