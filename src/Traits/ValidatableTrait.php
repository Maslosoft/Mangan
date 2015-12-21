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

namespace Maslosoft\Mangan\Traits;

use Maslosoft\Mangan\Interfaces\ValidatableInterface;
use Maslosoft\Mangan\Validator;

/**
 * ValidatableTrait
 * @see ValidatableInterface
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
trait ValidatableTrait
{

	/**
	 * Validator instance
	 * @var Validator
	 */
	private $_validator = null;

	/**
	 *
	 * @return string[]
	 * @Ignored
	 */
	public function getErrors()
	{
		return $this->_getValidator()->getErrors();
	}

	public function setErrors($errors)
	{
		$this->_getValidator()->setErrors($errors);
	}

	/**
	 *
	 * @return bool
	 * @Ignored
	 */
	public function validate()
	{
		return $this->_getValidator()->validate();
	}

	private function _getValidator()
	{
		if (null === $this->_validator)
		{
			$this->_validator = new Validator($this);
		}
		return $this->_validator;
	}

}
