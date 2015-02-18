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

namespace Maslosoft\Mangan\Interfaces;

/**
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
interface IOwnered
{

	/**
	 * Set class owner

	 * @return IModel Owner
	 */
	public function getOwner();

	/**
	 * Get class owner
	 * @param object $owner
	 */
	public function setOwner($owner);

	/**
	 * Get document root
	 * @return IModel Root document
	 */
	public function getRoot();
}
