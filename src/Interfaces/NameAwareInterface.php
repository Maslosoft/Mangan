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

use Maslosoft\Mangan\Helpers\Transformator;

/**
 * Use this interface to provide name for underlying class.
 *
 * @see Transformator
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
interface NameAwareInterface
{

	public function getName();

	public function setName($value);
}
