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

namespace Maslosoft\Mangan\Events;

/**
 * This event is raised when trying to instantiate embedded document but not class declaration was found
 * This can be used to rename classes, as class name can be stored in document
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class ClassNotFound extends ModelEvent
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
