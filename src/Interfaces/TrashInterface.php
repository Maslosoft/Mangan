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

use Maslosoft\Mangan\Model\Trash;
use Maslosoft\Mangan\Model\TrashItem;

/**
 * NOTE: When implementing this interface, some class properties are required.
 *
 * @see TrashItem
 * @see Trash
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
interface TrashInterface
{

	const EventBeforeTrash = 'beforeTrash';
	const EventAfterTrash = 'afterTrash';
	const EventBeforeRestore = 'beforeRestore';
	const EventAfterRestore = 'afterRestore';
	const ScenarioTrash = 'trash';
	const ScenarioRestore = 'restore';

}
