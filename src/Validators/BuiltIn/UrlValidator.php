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

use Maslosoft\Addendum\Interfaces\AnnotatedInterface;
use Maslosoft\Mangan\Interfaces\Validators\ValidatorInterface;
use Maslosoft\Mangan\Meta\ManganMeta;
use Maslosoft\Mangan\Validators\Traits\AllowEmpty;
use Maslosoft\Mangan\Validators\Traits\Messages;
use Maslosoft\Mangan\Validators\Traits\OnScenario;
use Maslosoft\Mangan\Validators\Traits\Safe;
use Maslosoft\Mangan\Validators\Traits\SkipOnError;

/**
 * UrlValidator
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class UrlValidator implements ValidatorInterface
{

	use AllowEmpty,
	  Messages,
	  OnScenario,
	  Safe,
	  SkipOnError;

	/**
	 * @Label('{attribute} must be valid url')
	 * @var string
	 */
	public $msgNotUrl = '';

	public function isValid(AnnotatedInterface $model, $attribute)
	{
		$valid = filter_var($model->$attribute, FILTER_VALIDATE_URL);
		if (!$valid)
		{
			$label = ManganMeta::create($model)->field($attribute)->label;
			$this->addError('msgNotUrl', ['{attribute}' => $label]);
			return false;
		}
		return true;
	}

}
