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
use Maslosoft\Mangan\Validators\BuiltIn\Base\SizeValidator;
use Maslosoft\Mangan\Validators\Traits\OnScenario;
use Maslosoft\Mangan\Validators\Traits\Safe;

/**
 * StringValidator
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class StringValidator extends SizeValidator implements ValidatorInterface
{

	use OnScenario,
	  Safe;

	/**
	 * @Label('{attribute} is too short (minimum is {min} characters)')
	 * @var string
	 */
	public $msgTooShort = '';

	/**
	 * @Label('{attribute} is too long (maximum is {max} characters)')
	 * @var string
	 */
	public $msgTooLong = '';

	/**
	 * @Label('{attribute} is of the wrong length (should be {length} characters)')
	 * @var string
	 */
	public $msgLength = '';

	public function isValid(AnnotatedInterface $model, $attribute)
	{
		$label = ManganMeta::create($model)->field($attribute)->label;
		$value = $model->$attribute;
		if (!is_string($value))
		{
			$this->addError('msgInvalid', ['{attribute}' => $label]);
			return false;
		}
		$length = mb_strlen($value);
		return $this->isValidValueOf($model, $attribute, $length, $label);
	}

}
