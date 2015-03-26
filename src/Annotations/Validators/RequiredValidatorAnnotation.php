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

/**
 * NOTE: This class is automatically generated from Yii validator class.
 * This is not actual validator. For validator class @see CRequiredValidator.
 */

/**
 * CRequiredValidator validates that the specified attribute does not have null or empty value.
 *
 * When using the {@link message} property to define a custom error message, the message
 * may contain additional placeholders that will be replaced with the actual content. In addition
 * to the "{attribute}" placeholder, recognized by all validators (see {@link CValidator}),
 * CRequiredValidator allows for the following placeholders to be specified:
 * <ul>
 * <li>{value}: replaced with the desired value {@link requiredValue}.</li>
 * </ul>
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @version $Id$
 * @package system.validators
 * @since 1.0
 */
class RequiredValidatorAnnotation extends ValidatorAnnotation
{

	/**
	 * @var mixed the desired value that the attribute must have.
	 * If this is null, the validator will validate that the specified attribute does not have null or empty value.
	 * If this is set as a value that is not null, the validator will validate that
	 * the attribute has a value that is the same as this property value.
	 * Defaults to null.
	 */
	public $requiredValue = NULL;

	/**
	 * @var boolean whether the comparison to {@link requiredValue} is strict.
	 * When this is true, the attribute value and type must both match those of {@link requiredValue}.
	 * Defaults to false, meaning only the value needs to be matched.
	 * This property is only used when {@link requiredValue} is not null.
	 */
	public $strict = false;

	public function init()
	{
		$this->_entity->validators = new ValidatorMeta(ParamsExpander::expand($this, [
					'requiredValue',
					'strict',
					'message',
					'skipOnError',
					'on',
					'safe',
					'enableClientValidation',
					'except',
					'proxy'
		]));
	}

}
