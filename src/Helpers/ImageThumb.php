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

namespace Maslosoft\Mangan\Helpers;

use PHPThumb\GD;

/**
 * Use this class instead of GD to prevent sizeof errors:
 *
 * @see https://github.com/masterexploder/PHPThumb/issues/131
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class ImageThumb extends GD
{
	/**
	 * This is to prevent sizeof error:
	 *
	 * @see https://github.com/masterexploder/PHPThumb/issues/131
	 * @var array
	 */
	protected $options = [];
}
