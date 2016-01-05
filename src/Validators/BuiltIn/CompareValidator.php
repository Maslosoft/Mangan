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
	  \Maslosoft\Mangan\Validators\Traits\Strict,
	  \Maslosoft\Mangan\Validators\Traits\OnScenario;

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

	/**
	 * @Label('{attribute} must be repeated exactly')
	 * @var string
	 */
	public $msgRepeat = '';

	/**
	 * @Label('{attribute} must not be equal to "{compareValue}"')
	 * @var string
	 */
	public $msgEq = '';

	/**
	 * @Label('{attribute} must be greater than "{compareValue}"')
	 * @var string
	 */
	public $msgGt = '';

	/**
	 * @Label('{attribute} must be greater than or equal to "{compareValue}"')
	 * @var string
	 */
	public $msgGte = '';

	/**
	 * @Label('{attribute} must be less than "{compareValue}"')
	 * @var string
	 */
	public $msgLt = '';

	/**
	 * @Label('{attribute} must be less than or equal to "{compareValue}"')
	 * @var string
	 */
	public $msgLte = '';

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
					$this->addError('msgRepeat', ['{attribute}' => $compareLabel]);
					return false;
				}
				break;
			case '!=':
				if (($this->strict && $value === $compareValue) || (!$this->strict && $value == $compareValue))
				{
					$this->addError('msgEq', ['{attribute}' => $label, '{compareValue}' => $compareValue]);
					return false;
				}
				break;
			case '>':
				if ($value <= $compareValue)
				{
					$this->addError('msgGt', ['{attribute}' => $label, '{compareValue}' => $compareValue]);
					return false;
				}
				break;
			case '>=':
				if ($value < $compareValue)
				{
					$this->addError('msgGte', ['{attribute}' => $label, '{compareValue}' => $compareValue]);
					return false;
				}
				break;
			case '<':
				if ($value >= $compareValue)
				{
					$this->addError('msgLt', ['{attribute}' => $label, '{compareValue}' => $compareValue]);
					return false;
				}
				break;
			case '<=':
				if ($value > $compareValue)
				{
					$this->addError('msgLte', ['{attribute}' => $label, '{compareValue}' => $compareValue]);
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
