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

namespace Maslosoft\Mangan\Traits\Model;

use Maslosoft\Addendum\Interfaces\AnnotatedInterface;
use Maslosoft\Mangan\Interfaces\SimpleTreeInterface;

/**
 * Embed Tree Trait
 * @see SimpleTreeInterface
 * @author Piotr
 */
trait EmbedTreeTrait
{

	/**
	 * @EmbeddedArray
	 * @var AnnotatedInterface[]
	 */
	public $children = [];

}
