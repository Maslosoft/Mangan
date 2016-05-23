<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Maslosoft\Mangan\Events;

use Maslosoft\Addendum\Interfaces\AnnotatedInterface;
use Maslosoft\Mangan\Model\Trash;

/**
 * RestoreEvent
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class RestoreEvent extends ModelEvent
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
