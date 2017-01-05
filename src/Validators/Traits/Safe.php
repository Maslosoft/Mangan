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

namespace Maslosoft\Mangan\Validators\Traits;

/**
 * Use this trait to add `strict` field
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
trait Safe
{

	/**
	 * When this is true atrtibute is considered safe, as it is validated
	 * @var bool
	 */
	public $safe = true;

}
