<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Maslosoft\Mangan\Traits;

use Maslosoft\Mangan\Interfaces\IOwnered;

/**
 * OwneredTrait
 * @see IOwnered
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
trait OwneredTrait
{

	private $owner = null;

	/**
	 * Set class owner

	 * @return IModel Owner
	 */
	public function getOwner()
	{
		return $this->owner;
	}

	/**
	 * Get document root
	 * @return IModel Root document
	 */
	public function getRoot()
	{
		if ($this->owner instanceof IOwnered && $this->owner !== null)
		{
			return $this->owner->getRoot();
		}
		else
		{
			return $this;
		}
	}

	/**
	 * Get class owner
	 * @param IModel $owner
	 */
	public function setOwner($owner)
	{
		$this->owner = $owner;
	}

}
