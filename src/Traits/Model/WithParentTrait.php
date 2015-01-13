<?php

namespace Maslosoft\Models\Traits;

use MongoId;

/**
 * This is trait for models having parent element
 *
 * @author Piotr
 */
trait WithParentTrait
{

	/**
	 * @SafeValidator
	 * @Sanitizer('MongoStringId')
	 * @var MongoId
	 */
	public $parentId = null;

}
