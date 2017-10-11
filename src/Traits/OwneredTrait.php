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

namespace Maslosoft\Mangan\Traits;

use Maslosoft\Addendum\Interfaces\AnnotatedInterface;
use Maslosoft\Mangan\Interfaces\OwneredInterface;

/**
 * This trait provides basic implemention on ownering of documents. This allows
 * sub-document to get it's parent or root document.
 *
 * When using this trait, it is recommended that all classes in composition to
 * implement `OwneredInterface` or use this trait.
 *
 * **NOTE:** Currently it's implementation does **not work instantly**. Owner is
 * set when transforming objects, which include save/load.
 *
 * @see OwneredInterface
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
trait OwneredTrait
{

	/**
	 * Owner reference or null if it's root object
	 * @var AnnotatedInterface|null
	 */
	private $_owner = null;

	/**
	 * Get document owner.
	 *
	 * This might return `null` if there is no owner.
	 *
	 * @return AnnotatedInterface Owner
	 * @Ignored
	 */
	public function getOwner()
	{
		return $this->_owner;
	}

	/**
	 * Get document root. This will traverse through objects
	 * hierarchy, untill owner-less document is found.
	 *
	 * This might return same object instance if there is no owner.
	 * 
	 * The return value is *guaranteed* to be document instance.
	 *
	 *
	 *
	 * @return AnnotatedInterface Root document
	 * @Ignored
	 */
	public function getRoot()
	{
		if ($this->_owner instanceof OwneredInterface && $this->_owner !== null)
		{
			return $this->_owner->getRoot();
		}
		else
		{
			return $this;
		}
	}

	/**
	 * Set class owner
	 *
	 * @param AnnotatedInterface|null $owner
	 * @Ignored
	 */
	public function setOwner(AnnotatedInterface $owner = null)
	{
		$this->_owner = $owner;
	}

}
