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

use Maslosoft\Addendum\Interfaces\AnnotatedInterface;
use Maslosoft\Mangan\Criteria;
use Maslosoft\Mangan\Finder;
use Maslosoft\Mangan\Helpers\PkManager;
use Maslosoft\Mangan\Interfaces\Validators\ValidatorInterface;
use Maslosoft\Mangan\Meta\ManganMeta;
use Maslosoft\Mangan\ScenarioManager;
use Maslosoft\Mangan\Validators\Traits\AllowEmpty;
use Maslosoft\Mangan\Validators\Traits\Messages;
use Maslosoft\Mangan\Validators\Traits\OnScenario;
use Maslosoft\Mangan\Validators\Traits\Safe;
use Maslosoft\Mangan\Validators\Traits\SkipOnError;

/**
 * UniqueValidator class file.
 *
 * @author Ianaré Sévi
 * @author Florian Fackler <florian.fackler@mintao.com>
 * @link http://mintao.com
 * @copyright Copyright (c) 2008-2010 Yii Software LLC
 * @license New BSD license
 */

/**
 * UniqueValidator validates that the attribute value is unique in the corresponding database table.
 *
 * @author Florian Fackler <florian.fackler@mintao.com>
 * @version $Id$
 * @package system.validators
 * @since 1.0
 */
class UniqueValidator implements ValidatorInterface
{

	use AllowEmpty,
	  SkipOnError,
	  Messages,
	  OnScenario,
	  Safe;

	/**
	 * @var string the document class name that should be used to
	 * look for the attribute value being validated. Defaults to null, meaning using
	 * the class of the object currently being validated.
	 *
	 * TODO Not implemented
	 *
	 * @see attributeName
	 * @since 1.0.8
	 */
	public $className;

	/**
	 * @var string the ActiveRecord class attribute name that should be
	 * used to look for the attribute value being validated. Defaults to null,
	 * meaning using the name of the attribute being validated.
	 *
	 * @see className
	 * @since 1.0.8
	 */
	public $attributeName;

	/**
	 * @var array additional query criteria. This will be combined with the condition
	 * that checks if the attribute value exists in the corresponding table column.
	 * This array will be used to instantiate a {@link Criteria} object.
	 * @since 1.0.8
	 */
	public $criteria = [];

	/**
	 * @Label('{attribute} "{value}" has already been taken')
	 * @var string
	 */
	public $msgTaken = '';

	/**
	 * Validates the attribute of the object.
	 * If there is any error, the error message is added to the object.
	 * @param AnnotatedInterface $model the object being validated
	 * @param string $attribute the attribute being validated
	 */
	public function isValid(AnnotatedInterface $model, $attribute)
	{
		$value = $model->$attribute;
		if ($this->allowEmpty && empty($value))
		{
			return true;
		}

		$className = empty($this->className) ? get_class($model) : $this->className;

		$compareModel = new $className;

		$criteria = (new Criteria)->decorateWith($compareModel);
		$criteria->addCond($attribute, '==', $value);

		if ($this->criteria !== [])
		{
			$criteria->mergeWith($this->criteria);
		}
		ScenarioManager::setScenario($compareModel, ValidatorInterface::ScenarioValidate);
		$finder = new Finder($compareModel);

		$found = $finder->find($criteria);

		// Not found entirely
		if (null === $found)
		{
			return true;
		}

		// Same pk
		if (PkManager::compare($found, $model))
		{
			return true;
		}
		$label = ManganMeta::create($model)->field($attribute)->label;
		$this->addError('msgTaken', ['{attribute}' => $label, '{value}' => $value]);
		return false;
	}

}
