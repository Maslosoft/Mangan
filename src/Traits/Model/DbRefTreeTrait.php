<?php

/**
 * This software package is licensed under AGPL or Commercial license.
 *
 * @package   maslosoft/mangan
 * @licence   AGPL or Commercial
 * @copyright Copyright (c) Piotr Masełkowski <pmaselkowski@gmail.com>
 * @copyright Copyright (c) Maslosoft
 * @copyright Copyright (c) Others as mentioned in code
 * @link      https://maslosoft.com/mangan/
 */

namespace Maslosoft\Mangan\Traits\Model;

use Maslosoft\Addendum\Interfaces\AnnotatedInterface;

/**
 * DbRef Tree Trait
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
trait DbRefTreeTrait
{

	/**
	 * @DbRefArray
	 * @var AnnotatedInterface[]
	 */
	public $children = [];

}
