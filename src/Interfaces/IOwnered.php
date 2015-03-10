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

namespace Maslosoft\Mangan\Interfaces;

use Maslosoft\Addendum\Interfaces\IAnnotated;

/**
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
interface IOwnered
{

	/**
	 * Set class owner

	 * @return IAnnotated Owner
	 */
	public function getOwner();

	/**
	 * Get class owner
	 * @param IAnnotated|null $owner
	 */
	public function setOwner(IAnnotated $owner = null);

	/**
	 * Get document root
	 * @return IAnnotated Root document
	 */
	public function getRoot();
}
