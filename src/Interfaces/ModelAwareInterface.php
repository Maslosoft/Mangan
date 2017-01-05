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
interface ModelAwareInterface
{

	/**
	 * Get currently used model
	 * @return AnnotatedInterface
	 */
	public function getModel();

	/**
	 * Set model for further use
	 * @param AnnotatedInterface $model
	 */
	public function setModel(AnnotatedInterface $model);
}
