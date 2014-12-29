<?php

/**
 * @licence For licence @see LICENCE.html
 * 
 * @copyright Maslosoft
 * @link http://maslosoft.com/
 */

namespace Maslosoft\Mangan\Events;

/**
 * This event is raised when trying to instantiate embedded document but not class declaration was found
 * This can be used to rename classes, as class name can be stored in document
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class ClassNotFound extends Event
{

	/**
	 * Not found class name
	 * @var string
	 */
	public $notFound = '';

	/**
	 * Replacement for notFound class
	 * @var string
	 */
	public $replacement = '';

}
