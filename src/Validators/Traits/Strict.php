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

namespace Maslosoft\Mangan\Validators\Traits;

/**
 * Use this trait to add `strict` field
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
trait Strict
{

	/**
	 * When this is true, the attribute value and type must both match.
	 * Defaults to false, meaning only the value needs to be matched.
	 * @var bool Whether the comparison is strict.
	 */
	public $strict = false;

}
