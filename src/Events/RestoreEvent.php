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

namespace Maslosoft\Mangan\Events;

use Maslosoft\Addendum\Interfaces\AnnotatedInterface;
use Maslosoft\Mangan\Model\Trash;

/**
 * RestoreEvent
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class RestoreEvent extends TrashEvent
{

	/**
	 * Trashed item instance
	 * @var AnnotatedInterface
	 */
	private $trashed = null;

	/**
	 *
	 * @return Trash
	 */
	public function getTrashed()
	{
		return $this->trashed;
	}

	/**
	 *
	 * @param AnnotatedInterface $trashed
	 * @return RestoreEvent
	 */
	public function setTrashed(AnnotatedInterface $trashed)
	{
		$this->trashed = $trashed;
		return $this;
	}

}
