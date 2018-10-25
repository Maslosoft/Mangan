<?php

/**
 * This software package is licensed under AGPL or Commercial license.
 *
 * @package   maslosoft/mangan
 * @licence   AGPL or Commercial
 * @copyright Copyright (c) Piotr Masełkowski <pmaselkowski@gmail.com>
 * @copyright Copyright (c) Maslosoft
 * @copyright Copyright (c) Others as mentioned in code
 * @link      https://maslosoft.com/mangan/
 */

namespace Maslosoft\Mangan;

use InvalidArgumentException;
use Maslosoft\Addendum\Interfaces\AnnotatedInterface;
use Maslosoft\Mangan\Helpers\Validator\Factory;
use Maslosoft\Mangan\Interfaces\EntityManagerInterface;
use Maslosoft\Mangan\Interfaces\OwneredInterface;
use Maslosoft\Mangan\Interfaces\ValidatableInterface;
use Maslosoft\Mangan\Meta\DocumentPropertyMeta;
use Maslosoft\Mangan\Meta\DocumentTypeMeta;
use Maslosoft\Mangan\Meta\ManganMeta;
use Maslosoft\Mangan\Meta\ValidatorMeta;

/**
 * Validator
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class Validator implements ValidatableInterface
{

	/**
	 * Model instance
	 * @var AnnotatedInterface
	 */
	private $model = null;

	/**
	 * Metadata for model
	 * @var ManganMeta
	 */
	private $meta = null;

	/**
	 * Array of error messages.
	 * Keys are field names, secondary keys are numeric
	 * @var string[][]
	 */
	private $errors = [];

	public function __construct(AnnotatedInterface $model)
	{
		$this->model = $model;
		$this->meta = ManganMeta::create($this->model);

		// Ensure that errors array is initialized - even if does not have validators
		foreach (array_keys($this->meta->fields()) as $name)
		{
			$this->errors[$name] = [];
		}
	}

	/**
	 * Validate model, optionally only selected fields
	 * @param string[] $fields
	 * @return boolean
	 */
	public function validate($fields = [])
	{
		$valid = [];
		if (empty($fields))
		{
			$fields = array_keys($this->meta->fields());
		}

		$meta = $this->meta->type();
		// Model validators
		if ($this->hasValidators($meta))
		{
			$typeName = $meta->name;
			// Reset errors
			$this->errors[$typeName] = [];
			$valid[] = (int)$this->validateField($typeName, $meta->validators);
		}

		foreach ($fields as $name)
		{
			$fieldMeta = $this->meta->field($name);

			// Reset errors
			$this->errors[$name] = [];

			// Check if meta for field exists
			assert(!empty($fieldMeta), new InvalidArgumentException(sprintf("Unknown field `%s` in model `%s`", $name, get_class($this->model))));

			// Validate if it is applicable
			if ($this->hasValidators($fieldMeta))
			{
				$valid[] = (int)$this->validateField($name, $fieldMeta->validators);
			}

			// Validate sub documents
			if ($this->haveSubObjects($fieldMeta) && $this->shouldValidate($this->model, $fieldMeta))
			{
				if (is_array($this->model->$name))
				{
					// Handle arrays of documents
					foreach ($this->model->$name as $fieldIndex => $model)
					{
						$errors = [];
						$valid[] = (int)$isValid = $this->validateEntity($model, $errors);
						if (!$isValid)
						{
							$errors = [
								$name => [
									$fieldIndex => $errors
								]
							];
							$this->setErrors($errors);
						}
					}
				}
				elseif (!empty($this->model->$name))
				{
					$model = $this->model->$name;
					$errors = [];
					$valid[] = (int)$isValid = $this->validateEntity($model, $errors);
					if (!$isValid)
					{
						$errors = [
							$name => $errors
						];
						$this->setErrors($errors);
					}
				}
			}
		}

		$areAllValid = count($valid) === array_sum($valid);

		// For easier debug
		if ($areAllValid)
		{
			return true;
		}
		return false;
	}

	/**
	 * Whether field has any validators
	 * @param DocumentTypeMeta|DocumentPropertyMeta $meta
	 * @return bool
	 */
	private function hasValidators($meta)
	{
		assert($meta instanceof DocumentTypeMeta || $meta instanceof DocumentPropertyMeta);
		return !empty($meta->validators);
	}

	/**
	 * Check whether field has sub object or sub objects
	 * @param DocumentPropertyMeta $fieldMeta
	 * @return bool
	 */
	private function haveSubObjects(DocumentPropertyMeta $fieldMeta)
	{
		return $fieldMeta->owned;
	}

	/**
	 * Check whether field should be validated.
	 *
	 * @param AnnotatedInterface   $model
	 * @param DocumentPropertyMeta $fieldMeta
	 * @return bool
	 */
	private function shouldValidate(AnnotatedInterface $model, DocumentPropertyMeta $fieldMeta)
	{
		// Skip fields that are owned...
		if ($fieldMeta->owned)
		{
			// ... and not updatable
			if (!$fieldMeta->updatable)
			{
				return false;
			}
		}

		// Check if should validate when saving and removing...
		if (AspectManager::hasAspect($model, EntityManagerInterface::AspectSaving) || AspectManager::hasAspect($model, EntityManagerInterface::AspectRemoving))
		{
			// ... when field is not persistent.
			// This is to speed up validation and prevent false
			// positive validations.
			if (!$fieldMeta->persistent)
			{
				return false;
			}
		}
		return true;
	}

	/**
	 * Validate single sub object entity. The `$errors`
	 * parameter will contain error messages passed by
	 * reference from this method.
	 *
	 * @param AnnotatedInterface $model
	 * @param string[]           $errors
	 * @return bool
	 */
	private function validateEntity(AnnotatedInterface $model, &$errors = [])
	{
		// Ensure owner, as validation might rely on it
		if ($model instanceof OwneredInterface)
		{
			$model->setOwner($this->model);
		}
		assert($model instanceof AnnotatedInterface);
		// Handle single documents
		$validator = new Validator($model);
		$isValid = $validator->validate();
		$errors = $validator->getErrors();
		return $isValid;
	}

	/**
	 * Validate single, simple field
	 * @param string          $name
	 * @param ValidatorMeta[] $validators
	 * @return bool
	 */
	private function validateField($name, $validators)
	{
		$valid = [];
		foreach ($validators as $validatorMeta)
		{
			// Filter out validators based on scenarios
			if (!empty($validatorMeta->on))
			{
				$on = (array)$validatorMeta->on;
				$enabled = false;
				foreach ($on as $scenario)
				{
					if ($scenario === ScenarioManager::getScenario($this->model))
					{
						$enabled = true;
						break;
					}
				}
				if (!$enabled)
				{
					continue;
				}
			}
			if (!empty($validatorMeta->except))
			{
				$except = (array)$validatorMeta->except;
				$enabled = true;
				foreach ($except as $scenario)
				{
					if ($scenario === ScenarioManager::getScenario($this->model))
					{
						$enabled = false;
						break;
					}
				}
				if (!$enabled)
				{
					continue;
				}
			}


			// Create validator and validate
			$validator = Factory::create($this->model, $validatorMeta, $name);
			if ($validator->isValid($this->model, $name))
			{
				$valid[] = true;
			}
			else
			{
				$valid[] = false;
				$this->errors[$name] = array_merge($this->errors[$name], $validator->getErrors());

				// Set errors to model instance if it implements ValidatableInterface
				if ($this->model instanceof ValidatableInterface)
				{
					$this->model->setErrors($this->errors);
				}
			}
		}
		return count($valid) === array_sum($valid);
	}

	public function getErrors()
	{
		return $this->errors;
	}

	public function setErrors($errors)
	{
		foreach ($errors as $field => $fieldErrors)
		{
			$this->errors[$field] = $fieldErrors;
		}
	}

}
