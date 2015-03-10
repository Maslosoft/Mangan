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

use Maslosoft\Addendum\Interfaces\IAnnotated;
use Maslosoft\Mangan\Criteria;
use Maslosoft\Mangan\Finder;
use Maslosoft\Mangan\Helpers\PkManager;
use Maslosoft\Mangan\Interfaces\IValidator;

/**
 * CUniqueValidator class file.
 *
 * @author Ianaré Sévi
 * @author Florian Fackler <florian.fackler@mintao.com>
 * @link http://mintao.com
 * @copyright Copyright (c) 2008-2010 Yii Software LLC
 * @license New BSD license
 */

/**
 * CUniqueValidator validates that the attribute value is unique in the corresponding database table.
 *
 * @author Florian Fackler <florian.fackler@mintao.com>
 * @version $Id$
 * @package system.validators
 * @since 1.0
 */
class UniqueValidator implements IValidator
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

	/**
	 * Validates the attribute of the object.
	 * If there is any error, the error message is added to the object.
	 * @param IAnnotated $model the object being validated
	 * @param string $attribute the attribute being validated
	 */
	protected function isValid(IAnnotated $model, $attribute)
	{
		$value = $model->$attribute;
		if ($this->allowEmpty && $this->isEmpty($value))
		{
			return true;
		}

		$criteria = (new Criteria)->decorateWith($model);
		$criteria->addCond($attribute, '==', $value);

		if ($this->criteria !== [])
		{
			$criteria->mergeWith($this->criteria);
		}

		$finder = new Finder($model);

		$found = $finder->find($criteria);

		// Not found entirely
		if ($found)
		{
			return true;
		}

		// Same pk
		/**
		 * TODO investigate if it's ok to check like that
		 */
		if (PkManager::prepareFromModel($found)->getConditions() === PkManager::prepareFromModel($model)->getConditions())
		{
			return true;
		}
		$message = $this->message !== null ? $this->message : '{attribute} "{value}" has already been taken.';
		$this->addError($model, $attribute, $message, ['{value}' => $value]);
		return false;
	}

	public function addError($message)
	{

	}

}
