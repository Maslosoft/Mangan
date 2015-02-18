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
	 * @return object Root document
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
	 * @param object $owner
	 */
	public function setOwner($owner)
	{
		$this->owner = $owner;
	}

}
