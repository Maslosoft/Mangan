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
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 * @property string $name Name of event
 * @property object $sender Sender
 * @property mixed $data Event data
 */
interface IEvent
{

	/**
	 * Ensure implementing class has this fields
	 */
//	const RequireFields = [
//		'name',
//		'sender',
//		'data'
//	];

}
