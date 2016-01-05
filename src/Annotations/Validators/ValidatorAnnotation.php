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

namespace Maslosoft\Mangan\Annotations\Validators;

use Maslosoft\Addendum\Helpers\ParamsExpander;
use Maslosoft\Mangan\Meta\ManganPropertyAnnotation;
use Maslosoft\Mangan\Meta\ValidatorMeta;
use Maslosoft\Mangan\Validators\Proxy\ClassValidatorProxy;

/**
 * Base class for validator annotations
 *
 * @author Piotr
 */
class ValidatorAnnotation extends ManganPropertyAnnotation
{

	const Ns = __NAMESPACE__;

	/**
	 * @var string the user-defined error message. Different validators may define various
	 * placeholders in the message that are to be replaced with actual values. All validators
	 * recognize "{attribute}" placeholder, which will be replaced with the label of the attribute.
	 */
	public $message;

	/**
	 * @var boolean whether this validation rule should be skipped when there is already a validation
	 * error for the current attribute. Defaults to false.
	 * @since 1.1.1
	 */
	public $skipOnError = false;

	/**
	 * @var array list of scenarios that the validator should be applied.
	 * Each array value refers to a scenario name with the same name as its array key.
	 */
	public $on;

	/**
	 * @var boolean whether attributes listed with this validator should be considered safe for massive assignment.
	 * Defaults to true.
	 * @since 1.1.4
	 */
	public $safe = true;

	/**
	 * @var array list of scenarios that the validator should not be applied to.
	 * Each array value refers to a scenario name with the same name as its array key.
	 * @since 1.1.11
	 */
	public $except;

	/**
	 * Validator proxy class
	 * @var string
	 */
	public $proxy = '';
	public $class = '';
	public $value = '';

	public function init()
	{
		$params = [
			'class'
		];
		if (is_string($this->value))
		{
			$this->class = $this->value;
		}
		else
		{
			foreach (array_keys($this->value) as $key)
			{
				if (!is_numeric($key))
				{
					$params[] = $key;
				}
			}
		}
		$this->proxy = ClassValidatorProxy::class;
		$this->_entity->validators[] = new ValidatorMeta(ParamsExpander::expand($this, $params));
	}

}
