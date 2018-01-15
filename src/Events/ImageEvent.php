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

namespace Maslosoft\Mangan\Events;

use Maslosoft\Addendum\Interfaces\AnnotatedInterface;

/**
 * Event raised before and after image rescaling
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class ImageEvent extends ModelEvent
{
	/**
	 * Path to temporary file
	 */
	public $path = '';
}
