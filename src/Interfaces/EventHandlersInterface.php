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

namespace Maslosoft\Mangan\Interfaces;

/**
 * Implement this interface to define event handlers for
 * specified classes.
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
interface EventHandlersInterface
{

	/**
	 * This method will be called only once, and should setup event handlers.
	 */
	public function setupHandlers();
}
