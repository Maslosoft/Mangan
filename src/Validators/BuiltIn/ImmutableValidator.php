<?php

/**
 * This software package is licensed under AGPL or Commercial license.
 *
 * @package maslosoft/mangan
 * @licence AGPL or Commercial
 * @copyright Copyright (c) Piotr Masełkowski <pmaselkowski@gmail.com>
 * @copyright Copyright (c) Maslosoft
 * @copyright Copyright (c) Others as mentioned in code
 * @link https://maslosoft.com/mangan/
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
use Maslosoft\Mangan\Validators\Traits\When;

/**
 * ImmutableValidator validates that the attribute value
 * is same as in the stored in database, if it was stored already.
 *
 * @author Florian Fackler <florian.fackler@mintao.com>
 * @version $Id$
 * @package system.validators
 * @since 1.0
 */
class ImmutableValidator implements ValidatorInterface
{

	use AllowEmpty,
	  SkipOnError,
	  Messages,
	  OnScenario,
	  Safe,
	  When;

	/**
	 * Set this value to check against trueish value stored in database.
	 * If empty this will check for validated attribute.
	 *
	 * @var string
	 */
	public $against = '';

	/**
	 * @var string the document class name that should be used to
	 * look for the attribute value being validated. Defaults to null, meaning using
	 * the class of the object currently being validated.
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
	 * @Label('{attribute} cannot be changed once set')
	 * @var string
	 */
	public $msgImmutable = '';

	/**
	 * Validates the attribute of the object.
	 * If there is any error, the error message is added to the object.
	 * @param AnnotatedInterface $model the object being validated
	 * @param string $attribute the attribute being validated
	 */
	public function isValid(AnnotatedInterface $model, $attribute)
	{
		if (!$this->whenValidate($model))
		{
			return true;
		}
		$value = $model->$attribute;
		if ($this->allowEmpty && empty($value))
		{
			return true;
		}

		$className = empty($this->className) ? get_class($model) : $this->className;

		$compareModel = new $className;

		$pk = PkManager::getFromModel($model);
		PkManager::applyToModel($compareModel, $pk);
		$criteria = PkManager::prepareFromModel($compareModel);

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

		// Decide against which field to check
		if (empty($this->against))
		{
			$against = $attribute;
		}
		else
		{
			$against = $this->against;
		}

		// Not stored in DB
		if (empty($found->$against))
		{
			return true;
		}

		// Stored in DB, but value is same
		if ($found->$attribute === $model->$attribute)
		{
			return true;
		}

		$label = ManganMeta::create($model)->field($attribute)->label;
		$this->addError('msgImmutable', ['{attribute}' => $label, '{value}' => $value]);
		return false;
	}

}
