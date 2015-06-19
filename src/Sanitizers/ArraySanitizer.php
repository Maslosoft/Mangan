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
 * ArraySanitizer
 * NOTE: This should NOT be used directly.
 * Use &commat;SanitizerArray annotation instead.
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class ArraySanitizer implements SanitizerInterface
{

	/**
	 * Sanitizer instance
	 * @var SanitizerInterface
	 */
	private $_sanitizer = null;

	public function __construct(SanitizerInterface $sanitizer)
	{
		$this->_sanitizer = $sanitizer;
	}

	public function read($model, $dbValue)
	{
		$result = [];
		foreach ((array) $dbValue as $key => $value)
		{
			$result[$key] = $this->_sanitizer->read($model, $value);
		}
		return $result;
	}

	public function write($model, $phpValue)
	{
		$result = [];
		foreach ((array) $phpValue as $key => $value)
		{
			$result[$key] = $this->_sanitizer->write($model, $value);
		}
		return $result;
	}

}
