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

use Maslosoft\Mangan\Events\ModelEvent;
use Maslosoft\Mangan\Events\TrashEvent;
use Maslosoft\Mangan\Interfaces\TrashInterface;

/**
 * TrashEvent
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class TrashEvent extends ModelEvent
{

	/**
	 *
	 * @var TrashInterface
	 */
	private $trash = null;

	/**
	 * Get trash model used to store trashed data.
	 * @return TrashInterface
	 */
	public function getTrash()
	{
		return $this->trash;
	}

	/**
	 * Set trash model used to store trashed data.
	 * @param TrashInterface $trash
	 * @return TrashEvent
	 */
	public function setTrash(TrashInterface $trash)
	{
		$this->trash = $trash;
		return $this;
	}

}
