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

use Maslosoft\Mangan\Interfaces\Sanitizers\Property\SanitizerInterface;

/**
 * Callback sanitizer
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class CallbackSanitizer implements SanitizerInterface
{

	/**
	 * Callback to sanitize value when writing
	 * @var callable
	 */
	public $write = null;

	/**
	 * Callback to sanitize value when reading
	 * @var callable
	 */
	public $read = null;

	public function read($model, $dbValue)
	{
		if (null !== $this->read)
		{
			return call_user_func($this->read, $dbValue);
		}
		return $dbValue;
	}

	public function write($model, $phpValue)
	{
		if (null !== $this->write)
		{
			return call_user_func($this->write, $phpValue);
		}
		return $phpValue;
	}

}
