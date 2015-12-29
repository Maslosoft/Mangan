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

namespace Maslosoft\Mangan;

use InvalidArgumentException;
use Maslosoft\Addendum\Interfaces\AnnotatedInterface;
use Maslosoft\Mangan\Helpers\Validator\Factory;
use Maslosoft\Mangan\Interfaces\ValidatableInterface;
use Maslosoft\Mangan\Meta\ManganMeta;

/**
 * Validator
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class Validator implements ValidatableInterface
{

	const EventBeforeValidate = 'beforeValidate';
	const EventAfterValidate = 'afterValidate';

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
		foreach ($fields as $name)
		{
			$fieldMeta = $this->meta->field($name);

			// Reset errors
			$this->errors[$name] = [];

			// Check if meta for field exists
			if (empty($fieldMeta))
			{
				throw new InvalidArgumentException(sprintf("Unknown field `%s` in model `%s`", $name, get_class($this->model)));
			}

			// Validate sub documents
			if ($fieldMeta->owned)
			{
				if (is_array($this->model->$name))
				{
					foreach ($this->model->$name as $model)
					{
						$validator = new Validator($model);
						$valid[] = (int) $validator->validate();
					}
				}
				elseif (!empty($this->model->$name))
				{
					$validator = new Validator($this->model->$name);
					$valid[] = (int) $validator->validate();
				}
			}

			// Skip field without validators
			if (empty($fieldMeta->validators))
			{
				continue;
			}
			$valid[] = (int) $this->validateField($name, $fieldMeta);
		}
		return count($valid) === array_sum($valid);
	}

	private function validateField($name, Meta\DocumentPropertyMeta $meta)
	{
		$valid = [];
		foreach ($meta->validators as $validatorMeta)
		{
			$validator = Factory::create($this->model, $validatorMeta);
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
		foreach ($errors as $field => $errors)
		{
			$this->errors[$field] = $errors;
		}
	}

}
