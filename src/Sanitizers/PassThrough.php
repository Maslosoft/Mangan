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

use Maslosoft\Mangan\Helpers\Sanitizer\Sanitizer;
use Maslosoft\Mangan\Interfaces\Sanitizers\Property\SanitizerInterface;

/**
 * PassThrough
 * Empty sanitizer, does not change anything
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class PassThrough implements SanitizerInterface
{

	/**
	 * For internal use, to provide namespace to Sanitizer
	 * @see Sanitizer
	 */
	const Ns = __NAMESPACE__;

	public function read($model, $dbValue)
	{
		return $dbValue;
	}

	public function write($model, $phpValue)
	{
		return $phpValue;
	}

}
