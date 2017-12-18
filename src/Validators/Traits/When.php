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

namespace Maslosoft\Mangan\Validators\Traits;

/**
 * Validate when criteria is met.
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
trait When
{

	/**
	 * Set this to string - to check if property value is trueish:
	 *
	 * ```
	 * $when = 'myProperty';
	 * ```
	 *
	 * This will check if property value is not `false` and validate
	 * only if this property is trueish.
	 *
	 * Set this to array to check specified criteria:
	 *
	 * ```
	 * $when = [
	 * 		'firstProperty' => 1,
	 * 		'secondProperty' => true
	 * ];
	 * ```
	 *
	 * Will run validation only when those values of model properties
	 * are exact as provided.
	 *
	 * @var string|array
	 */
	public $when = null;

	public function whenValidate($model)
	{
		if (empty($this->when))
		{
			return true;
		}
		if (is_string($this->when))
		{
			$name = $this->when;
			if ($model->$name)
			{
				return true;
			}
		}
		elseif (is_array($this->when))
		{
			$conditions = [];
			foreach ($this->when as $name => $value)
			{
				$conditions[] = $model->$name === $value;
			}
			return count($conditions) === array_sum($conditions);
		}
		return false;
	}

}
