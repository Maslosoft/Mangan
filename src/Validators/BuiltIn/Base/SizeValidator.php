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

namespace Maslosoft\Mangan\Validators\BuiltIn\Base;

use Maslosoft\Mangan\Interfaces\Validators\ValidatorInterface;
use Maslosoft\Mangan\Meta\ManganMeta;
use Maslosoft\Mangan\Validators\BuiltIn\CountValidator;
use Maslosoft\Mangan\Validators\BuiltIn\StringValidator;
use Maslosoft\Mangan\Validators\Traits\AllowEmpty;
use Maslosoft\Mangan\Validators\Traits\Messages;

/**
 * Base class for size validators.
 *
 * This can be used as a base for validators checking sizes:
 *
 * * String Length
 * * Number of elements
 * * File size
 *
 * Override msg* attributes with custom `Label` annotations to
 * provide proper error messages.
 *
 * @see StringValidator
 * @see CountValidator
 * @see ValidatorInterface
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
abstract class SizeValidator
{

	use AllowEmpty,
	  Messages;

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
	 * @Label('{attribute} is too small')
	 * @var string
	 */
	public $msgTooShort = '';

	/**
	 * @Label('{attribute} is too large')
	 * @var string
	 */
	public $msgTooLong = '';

	/**
	 * @Label('{attribute} is of the wrong size')
	 * @var string
	 */
	public $msgLength = '';

	protected function isValidValueOf($model, $attribute, $value, $label = '')
	{
		if ($this->allowEmpty && empty($value))
		{
			return true;
		}
		if (empty($label))
		{
			$label = ManganMeta::create($model)->field($attribute)->label;
		}
		if (!is_int($value))
		{
			$this->addError('msgInvalid', ['{attribute}' => $label]);
			return false;
		}

		if ($this->min !== null && $value < $this->min)
		{
			if ($this->tooShort)
			{
				$this->addError($this->tooShort, ['{min}' => $this->min, '{attribute}' => $label]);
				return false;
			}
			$this->addError('msgTooShort', array('{min}' => $this->min, '{attribute}' => $label));
			return false;
		}
		if ($this->max !== null && $value > $this->max)
		{
			if ($this->tooLong)
			{
				$this->addError($this->tooLong, array('{max}' => $this->max, '{attribute}' => $label));
				return false;
			}
			$this->addError('msgTooLong', array('{max}' => $this->max, '{attribute}' => $label));
			return false;
		}
		if ($this->is !== null && $value !== $this->is)
		{
			$this->addError('msgLength', array('{length}' => $this->is, '{attribute}' => $label));
			return false;
		}
		return true;
	}

}
