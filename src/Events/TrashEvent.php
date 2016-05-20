<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Maslosoft\Mangan\Events;

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
	 * @return \Maslosoft\Mangan\Events\TrashEvent
	 */
	public function setTrash(TrashInterface $trash)
	{
		$this->trash = $trash;
		return $this;
	}

}
