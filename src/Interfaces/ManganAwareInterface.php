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

namespace Maslosoft\Mangan\Interfaces;

use Maslosoft\Mangan\Mangan;

/**
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
interface ManganAwareInterface
{

	/**
	 * Set mangan instance
	 * @param Mangan $mangan
	 */
	public function setMangan(Mangan $mangan);
}
