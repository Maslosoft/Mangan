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

namespace Maslosoft\Mangan\Interfaces;

use Maslosoft\Addendum\Interfaces\AnnotatedInterface;

/**
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
interface OwneredInterface
{

	/**
	 * Set class owner

	 * @return AnnotatedInterface Owner
	 */
	public function getOwner();

	/**
	 * Get class owner
	 * @param AnnotatedInterface|null $owner
	 */
	public function setOwner(AnnotatedInterface $owner = null);

	/**
	 * Get document root
	 * @return AnnotatedInterface Root document
	 */
	public function getRoot();
}
