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

namespace Maslosoft\Mangan\Interfaces\Events;

/**
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 * @property string $name Name of event
 * @property object $sender Sender
 * @property mixed $data Event data
 */
interface EventInterface
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
