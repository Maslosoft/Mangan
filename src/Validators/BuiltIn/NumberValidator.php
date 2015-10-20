<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Maslosoft\Mangan\Validators\BuiltIn;

use Maslosoft\Addendum\Interfaces\AnnotatedInterface;
use Maslosoft\Mangan\Interfaces\Validators\ValidatorInterface;
use Maslosoft\Mangan\Meta\ManganMeta;

/**
 * NumberValidator
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class NumberValidator implements ValidatorInterface
{

	use \Maslosoft\Mangan\Validators\Traits\AllowEmpty,
	  \Maslosoft\Mangan\Validators\Traits\Messages;

	/**
	 * @var boolean whether the attribute value can only be an integer. Defaults to false.
	 */
	public $integerOnly = false;

	/**
	 * @var integer|float upper limit of the number. Defaults to null, meaning no upper limit.
	 */
	public $max = NULL;

	/**
	 * @var integer|float lower limit of the number. Defaults to null, meaning no lower limit.
	 */
	public $min = NULL;

	/**
	 * @var string user-defined error message used when the value is too big.
	 */
	public $tooBig = NULL;

	/**
	 * @var string user-defined error message used when the value is too small.
	 */
	public $tooSmall = NULL;

	/**
	 * @var string the regular expression for matching integers.
	 * @since 1.1.7
	 */
	public $integerPattern = '/^\s*[+-]?\d+\s*$/';

	/**
	 * @var string the regular expression for matching numbers.
	 * @since 1.1.7
	 */
	public $numberPattern = '/^\s*[-+]?[0-9]*\.?[0-9]+([eE][-+]?[0-9]+)?\s*$/';

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
			$this->addError('{attribute} must be a number', ['{attribute}' => $label]);
			return false;
		}
		if (!is_numeric($value))
		{
			$this->addError('{attribute} must be a number', ['{attribute}' => $label]);
			return false;
		}
		if ($this->integerOnly)
		{

			if (!filter_var($value, FILTER_VALIDATE_INT))
			{
				$this->addError('{attribute} must be an integer', ['{attribute}' => $label]);
				return false;
			}
		}
		else
		{
			if (!filter_var($value, FILTER_VALIDATE_FLOAT))
			{
				$this->addError('{attribute} must be a number', ['{attribute}' => $label]);
				return false;
			}
		}
		if ($this->min !== null && $value < $this->min)
		{
			if (!empty($this->tooSmall))
			{
				$this->addError($this->tooSmall, ['{min}' => $this->min, '{attribute}' => $label]);
				return false;
			}
			$this->addError('{attribute} is too small (minimum is {min})', ['{min}' => $this->min, '{attribute}' => $label]);
			return false;
		}
		if ($this->max !== null && $value > $this->max)
		{
			if (!empty($this->tooBig))
			{
				$this->addError($this->tooBig, ['{max}' => $this->max, '{attribute}' => $label]);
			}
			$this->addError('{attribute} is too big (maximum is {max})', ['{max}' => $this->max, '{attribute}' => $label]);
			return false;
		}
		return true;
	}

}
