<?php

/**
 * @licence For licence @see LICENCE.html
 * 
 * @copyright Maslosoft
 * @link http://maslosoft.com/
 */

namespace Maslosoft\Mangan\Sanitizers;

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
	public function read($dbValue);

	/**
	 * This will be called when data is written back to database.
	 * This should return mongo acceptable value.
	 * @param mixed $phpValue
	 */
	public function write($phpValue);
}
