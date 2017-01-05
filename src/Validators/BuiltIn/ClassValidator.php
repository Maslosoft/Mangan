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

namespace Maslosoft\Mangan\Validators\BuiltIn;

use Maslosoft\Mangan\Interfaces\Validators\ValidatorInterface;

/**
 * This validator forwards validation to specified class.
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class ClassValidator implements ValidatorInterface
{

	use \Maslosoft\Mangan\Validators\Traits\AllowEmpty,
	  \Maslosoft\Mangan\Validators\Traits\Messages,
	  \Maslosoft\Mangan\Validators\Traits\Strict,
	  \Maslosoft\Mangan\Validators\Traits\OnScenario,
	  \Maslosoft\Mangan\Validators\Traits\Safe;

	public $class = '';

	public function isValid(\Maslosoft\Addendum\Interfaces\AnnotatedInterface $model, $attribute)
	{

	}

}
