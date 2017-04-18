<?php

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
