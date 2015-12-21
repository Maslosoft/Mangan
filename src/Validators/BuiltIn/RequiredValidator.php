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
 * RequiredValidator
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class RequiredValidator implements ValidatorInterface
{

	use \Maslosoft\Mangan\Validators\Traits\Strict,
	  \Maslosoft\Mangan\Validators\Traits\Messages;

	/**
	 * @var mixed the desired value that the attribute must have.
	 * If this is null, the validator will validate that the specified attribute does not have null or empty value.
	 * If this is set as a value that is not null, the validator will validate that
	 * the attribute has a value that is the same as this property value.
	 * Defaults to null.
	 */
	public $requiredValue = null;

	/**
	 * @var boolean whether the value should be trimmed with php trim() function when comparing strings.
	 * When set to false, the attribute value is not considered empty when it contains spaces.
	 * Defaults to true, meaning the value will be trimmed.
	 * @since 1.1.14
	 */
	public $trim = true;

	public function isValid(AnnotatedInterface $model, $attribute)
	{
		$value = $model->$attribute;
		$label = ManganMeta::create($model)->field($attribute)->label;
		if (!empty($this->requiredValue))
		{
			if (!$this->strict && $value != $this->requiredValue || $this->strict && $value !== $this->requiredValue)
			{
				$this->addError('{attribute} must be {value}.', ['{attribute}' => $label, '{value}' => $this->requiredValue]);
				return false;
			}
		}
		elseif ($this->isEmpty($value, $this->trim))
		{
			$this->addError('{attribute} cannot be blank.', ['{attribute}' => $label]);
			return false;
		}
		return true;
	}

	/**
	 * Checks if the given value is empty.
	 * A value is considered empty if it is null, an empty array, or the trimmed result is an empty string.
	 * Note that this method is different from PHP empty(). It will return false when the value is 0.
	 * @param mixed $value the value to be checked
	 * @param boolean $trim whether to perform trimming before checking if the string is empty. Defaults to false.
	 * @return boolean whether the value is empty
	 */
	protected function isEmpty($value, $trim = false)
	{
		return $value === null || $value === array() || $value === '' || $trim && is_scalar($value) && trim($value) === '';
	}

}
