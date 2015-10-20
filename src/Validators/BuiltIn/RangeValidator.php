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
 * RangeValidator
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class RangeValidator implements ValidatorInterface
{

	use \Maslosoft\Mangan\Validators\Traits\AllowEmpty,
	  \Maslosoft\Mangan\Validators\Traits\Messages,
	  \Maslosoft\Mangan\Validators\Traits\Strict;

	/**
	 * @var array list of valid values that the attribute value should be among
	 */
	public $range;

	/**
	 * @var boolean whether to invert the validation logic. Defaults to false. If set to true,
	 * the attribute value should NOT be among the list of values defined via {@link range}.
	 * @since 1.1.5
	 * */
	public $not = false;

	public function isValid(AnnotatedInterface $model, $attribute)
	{
		$value = $model->$attribute;
		if ($this->allowEmpty && empty($value))
		{
			return true;
		}
		if (!is_array($this->range))
		{
			$msg = sprintf('The "range" property must be specified with a list of values on attribute `%s` of model `%s`', $attribute, get_class($model));
			throw new InvalidArgumentException($msg);
		}
		$result = false;
		if ($this->strict)
		{
			$result = in_array($value, $this->range, true);
		}
		else
		{
			foreach ($this->range as $r)
			{
				$result = $r === '' || $value === '' ? $r === $value : $r == $value;
				if ($result)
				{
					break;
				}
			}
		}
		$label = ManganMeta::create($model)->field($attribute)->label;
		if (!$this->not && !$result)
		{
			$this->addError('{attribute} is not in the list', ['{attribute}' => $label]);
			return false;
		}
		elseif ($this->not && $result)
		{
			$this->addError('{attribute} is in the list', ['{attribute}' => $label]);
			return false;
		}
		return true;
	}

}
