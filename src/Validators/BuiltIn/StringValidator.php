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

namespace Maslosoft\Mangan\Validators\BuiltIn;

use Maslosoft\Addendum\Interfaces\AnnotatedInterface;
use Maslosoft\Mangan\Interfaces\Validators\ValidatorInterface;
use Maslosoft\Mangan\Meta\ManganMeta;
use Maslosoft\Mangan\Validators\Traits\AllowEmpty;
use Maslosoft\Mangan\Validators\Traits\Messages;
use Maslosoft\Mangan\Validators\Traits\OnScenario;
use Maslosoft\Mangan\Validators\Traits\Safe;

/**
 * StringValidator
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class StringValidator implements ValidatorInterface
{

	use AllowEmpty,
	  Messages,
	  OnScenario,
	  Safe;

	/**
	 * @var integer maximum length. Defaults to null, meaning no maximum limit.
	 */
	public $max;

	/**
	 * @var integer minimum length. Defaults to null, meaning no minimum limit.
	 */
	public $min;

	/**
	 * @var integer exact length. Defaults to null, meaning no exact length limit.
	 */
	public $is;

	/**
	 * @var string user-defined error message used when the value is too short.
	 * @deprecated use `msgTooShort` instead
	 */
	public $tooShort;

	/**
	 * @var string user-defined error message used when the value is too long.
	 * @deprecated use `msgTooLong` instead
	 */
	public $tooLong;

	/**
	 * @Label('{attribute} is invalid')
	 * @var string
	 */
	public $msgInvalid = '';

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
		$value = $model->$attribute;
		if ($this->allowEmpty && empty($value))
		{
			return true;
		}
		$label = ManganMeta::create($model)->field($attribute)->label;
		if (!is_scalar($value))
		{
			$this->addError('msgInvalid', ['{attribute}' => $label]);
			return false;
		}
		if (!is_string($value))
		{
			$this->addError('msgInvalid', ['{attribute}' => $label]);
			return false;
		}
		$length = mb_strlen($value);

		if ($this->min !== null && $length < $this->min)
		{
			if ($this->tooShort)
			{
				$this->addError($this->tooShort, ['{min}' => $this->min, '{attribute}' => $label]);
				return false;
			}
			$this->addError('msgTooShort', array('{min}' => $this->min, '{attribute}' => $label));
			return false;
		}
		if ($this->max !== null && $length > $this->max)
		{
			if ($this->tooLong)
			{
				$this->addError($this->tooLong, array('{max}' => $this->max, '{attribute}' => $label));
				return false;
			}
			$this->addError('msgTooLong', array('{max}' => $this->max, '{attribute}' => $label));
			return false;
		}
		if ($this->is !== null && $length !== $this->is)
		{
			$this->addError('msgLength', array('{length}' => $this->is, '{attribute}' => $label));
			return false;
		}
		return true;
	}

}
