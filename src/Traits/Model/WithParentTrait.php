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

namespace Maslosoft\Mangan\Traits\Model;

use Maslosoft\Mangan\Events\Handlers\ParentIdHandler;
use Maslosoft\Mangan\Sanitizers\MongoStringId;
use MongoId;

/**
 * This is trait for models having parent element
 *
 * @see ParentIdHandler
 * @author Piotr
 */
trait WithParentTrait
{

	/**
	 * @SafeValidator
	 * @Sanitizer(MongoStringId, nullable = true)
	 * @see MongoStringId
	 * @var MongoId
	 */
	public $parentId = null;

}
