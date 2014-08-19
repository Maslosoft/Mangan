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
	 * This will be called when getting value.
	 * This should return end user value.
	 * @param mixed $value
	 */
	public function get($value);

	/**
	 * This will be called when setting value.
	 * This should return db acceptable value
	 * @param mixed $value
	 */
	public function set($value);
}
