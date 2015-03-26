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
 * UniqueValidator
 * 
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class UniqueValidatorAnnotation extends ValidatorAnnotation
{

	/**
	 * @var boolean whether the attribute value can be null or empty. Defaults to true,
	 * meaning that if the attribute is empty, it is considered valid.
	 */
	public $allowEmpty = true;

	/**
	 * @var string the ActiveRecord class name that should be used to
	 * look for the attribute value being validated. Defaults to null, meaning using
	 * the class of the object currently being validated.
	 * You may use path alias to reference a class name here.
	 * @see attributeName
	 * @since 1.0.8
	 */
	public $className;

	/**
	 * @var string the ActiveRecord class attribute name that should be
	 * used to look for the attribute value being validated. Defaults to null,
	 * meaning using the name of the attribute being validated.
	 * @see className
	 * @since 1.0.8
	 */
	public $attributeName;

	/**
	 * @var array additional query criteria. This will be combined with the condition
	 * that checks if the attribute value exists in the corresponding table column.
	 * This array will be used to instantiate a {@link CDbCriteria} object.
	 * @since 1.0.8
	 */
	public $criteria = [];

	/**
	 * @var string the user-defined error message. The placeholders "{attribute}" and "{value}"
	 * are recognized, which will be replaced with the actual attribute name and value, respectively.
	 */
	public $message;

	/**
	 * @var boolean whether this validation rule should be skipped if when there is already a validation
	 * error for the current attribute. Defaults to true.
	 * @since 1.1.1
	 */
	public $skipOnError = true;

	public function init()
	{
		$this->_entity->validators[] = new ValidatorMeta(ParamsExpander::expand($this, [
					'allowEmpty',
					'className',
					'attributeName',
					'criteria',
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
