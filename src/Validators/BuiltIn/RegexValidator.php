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
 * RegexValidator
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class RegexValidator implements ValidatorInterface
{

	use \Maslosoft\Mangan\Validators\Traits\AllowEmpty,
	  \Maslosoft\Mangan\Validators\Traits\Messages;

	/**
	 * @var string the regular expression to be matched with
	 */
	public $pattern;

	/**
	 * @var boolean whether to invert the validation logic. Defaults to false. If set to true,
	 * the regular expression defined via {@link pattern} should NOT match the attribute value.
	 * @since 1.1.5
	 * */
	public $not = false;

	/**
	 * @Label('{attribute} has invalid value')
	 * @var string
	 */
	public $msgInvalid = '';

	public function isValid(AnnotatedInterface $model, $attribute)
	{
		$value = $model->$attribute;
		if ($this->allowEmpty && empty($value))
		{
			return true;
		}
		if ($this->pattern === null)
		{
			$msg = sprintf('The `pattern` property must be specified with a valid regular expression on attribute `%s` of model `%s`', $attribute, get_class($model));
			throw new InvalidArgumentException($msg);
		}
		$label = ManganMeta::create($model)->field($attribute)->label;
		if (!is_scalar($value))
		{
			$this->addError('msgInvalid', ['{attribute}' => $label]);
			return false;
		}
		$match = preg_match($this->pattern, $value);
		if ($this->not)
		{
			if ($match)
			{
				$this->addError('msgInvalid', ['{attribute}' => $label]);
				return false;
			}
		}
		else
		{
			if (!$match)
			{
				$this->addError('msgInvalid', ['{attribute}' => $label]);
				return false;
			}
		}
		return true;
	}

}
