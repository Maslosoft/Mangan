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

use Countable;
use Maslosoft\Addendum\Interfaces\AnnotatedInterface;
use Maslosoft\Mangan\Interfaces\Validators\ValidatorInterface;
use Maslosoft\Mangan\Meta\ManganMeta;
use Maslosoft\Mangan\Validators\BuiltIn\Base\SizeValidator;
use Maslosoft\Mangan\Validators\Traits\OnScenario;
use Maslosoft\Mangan\Validators\Traits\Safe;
use Maslosoft\Mangan\Validators\Traits\SkipOnError;
use Maslosoft\Mangan\Validators\Traits\When;

/**
 * CountValidator
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class CountValidator extends SizeValidator implements ValidatorInterface
{

	use OnScenario,
	  Safe,
	  SkipOnError,
	  When;

	/**
	 * @Label('There are not enough of {attribute} (minimum is {min})')
	 * @var string
	 */
	public $msgTooShort = '';

	/**
	 * @Label('There are too many of {attribute} (maximum is {max})')
	 * @var string
	 */
	public $msgTooLong = '';

	/**
	 * @Label('There must be exact {length} number of {attribute}')
	 * @var string
	 */
	public $msgLength = '';

	public function isValid(AnnotatedInterface $model, $attribute)
	{
		if (!$this->whenValidate($model))
		{
			return true;
		}
		$label = ManganMeta::create($model)->field($attribute)->label;
		if (!is_array($model->$attribute))
		{
			if (!$model->$attribute instanceof Countable)
			{
				$this->addError('msgInvalid', ['{attribute}' => $label]);
				return false;
			}
		}
		$value = count($model->$attribute);
		return $this->isValidValueOf($model, $attribute, $value, $label);
	}

}
