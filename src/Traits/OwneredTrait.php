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

use Maslosoft\Addendum\Interfaces\IAnnotated;
use Maslosoft\Mangan\Interfaces\IOwnered;

/**
 * OwneredTrait
 * @see IOwnered
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
trait OwneredTrait
{

	/**
	 * Owner reference or null if it's root object
	 * @var IAnnotated|null
	 */
	private $_owner = null;

	/**
	 * Set class owner

	 * @return IAnnotated Owner
	 */
	public function getOwner()
	{
		return $this->_owner;
	}

	/**
	 * Get document root
	 * @return IAnnotated Root document
	 */
	public function getRoot()
	{
		if ($this->_owner instanceof IOwnered && $this->_owner !== null)
		{
			return $this->_owner->getRoot();
		}
		else
		{
			return $this;
		}
	}

	/**
	 * Get class owner
	 * @param IAnnotated|null $owner
	 */
	public function setOwner(IAnnotated $owner = null)
	{
		$this->_owner = $owner;
	}

}
