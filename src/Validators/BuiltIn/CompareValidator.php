<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Maslosoft\Mangan\Validators\BuiltIn;

use InvalidArgumentException;
use Maslosoft\Addendum\Interfaces\AnnotatedInterface;
use Maslosoft\Mangan\Interfaces\Validators\ValidatorInterface;
use Maslosoft\Mangan\Meta\ManganMeta;

/**
 * CompareValidator
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class CompareValidator implements ValidatorInterface
{

	use \Maslosoft\Mangan\Validators\Traits\AllowEmpty,
	  \Maslosoft\Mangan\Validators\Traits\Messages,
	  \Maslosoft\Mangan\Validators\Traits\Strict;

	/**
	 * @var string the name of the attribute to be compared with
	 */
	public $compareAttribute;

	/**
	 * @var string the constant value to be compared with
	 */
	public $compareValue;

	/**
	 * @var string the operator for comparison. Defaults to '='.
	 * The followings are valid operators:
	 * <ul>
	 * <li>'=' or '==': validates to see if the two values are equal. If {@link strict} is true, the comparison
	 * will be done in strict mode (i.e. checking value type as well).</li>
	 * <li>'!=': validates to see if the two values are NOT equal. If {@link strict} is true, the comparison
	 * will be done in strict mode (i.e. checking value type as well).</li>
	 * <li>'>': validates to see if the value being validated is greater than the value being compared with.</li>
	 * <li>'>=': validates to see if the value being validated is greater than or equal to the value being compared with.</li>
	 * <li>'<': validates to see if the value being validated is less than the value being compared with.</li>
	 * <li>'<=': validates to see if the value being validated is less than or equal to the value being compared with.</li>
	 * </ul>
	 */
	public $operator = '=';

	public function isValid(AnnotatedInterface $model, $attribute)
	{
		$value = $model->$attribute;
		if ($this->allowEmpty && empty($value))
		{
			return true;
		}
		$label = ManganMeta::create($model)->field($attribute)->label;

		if ($this->compareValue !== null)
		{
			$compareLabel = $compareValue = $this->compareValue;
		}
		else
		{
			$compareAttribute = $this->compareAttribute === null ? $attribute . '_repeat' : $this->compareAttribute;
			$compareValue = $model->$compareAttribute;
			$compareLabel = ManganMeta::create($model)->field($compareAttribute)->label;
		}

		switch ($this->operator)
		{
			case '=':
			case '==':
				if (($this->strict && $value !== $compareValue) || (!$this->strict && $value != $compareValue))
				{
					$this->addError('{attribute} must be repeated exactly', ['{attribute}' => $compareLabel]);
					return false;
				}
				break;
			case '!=':
				if (($this->strict && $value === $compareValue) || (!$this->strict && $value == $compareValue))
				{
					$this->addError('{attribute} must not be equal to "{compareValue}"', ['{attribute}' => $label, '{compareValue}' => $compareValue]);
					return false;
				}
				break;
			case '>':
				if ($value <= $compareValue)
				{
					$this->addError('{attribute} must be greater than "{compareValue}"', ['{attribute}' => $label, '{compareValue}' => $compareValue]);
					return false;
				}
				break;
			case '>=':
				if ($value < $compareValue)
				{
					$this->addError('{attribute} must be greater than or equal to "{compareValue}"', ['{attribute}' => $label, '{compareValue}' => $compareValue]);
					return false;
				}
				break;
			case '<':
				if ($value >= $compareValue)
				{
					$this->addError('{attribute} must be less than "{compareValue}"', ['{attribute}' => $label, '{compareValue}' => $compareValue]);
					return false;
				}
				break;
			case '<=':
				if ($value > $compareValue)
				{
					$this->addError('{attribute} must be less than or equal to "{compareValue}"', ['{attribute}' => $label, '{compareValue}' => $compareValue]);
					return false;
				}
				break;
			default:
				$msg = sprintf('Invalid operator `%s` on attribute `%s` of model `%s`', $this->operator, $attribute, get_class($model));
				throw new InvalidArgumentException($msg);
		}
		return true;
	}

}
