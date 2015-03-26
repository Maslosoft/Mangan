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

namespace Maslosoft\Mangan\Interfaces\Sanitizers\Property;

/**
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
interface ISanitizer
{

	/**
	 * This will be called when data is read from mongo, and assigned to document object.
	 * This should return end user value.
	 * @param mixed $dbValue
	 */
	public function read($model, $dbValue);

	/**
	 * This will be called when data is written back to database.
	 * This should return mongo acceptable value.
	 * @param mixed $phpValue
	 */
	public function write($model, $phpValue);
}
