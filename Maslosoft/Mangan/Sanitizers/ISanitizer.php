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
	public function get($value);
	public function set($value);
}
