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

/**
 * ValidatableTrait
 * @see ValidatableInterface
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
trait ValidatableTrait
{

	/**
	 * Validator instance
	 * @var \Maslosoft\Mangan\Validator
	 */
	private $_validator = null;

	/**
	 *
	 * @return string[]
	 * @Ignore
	 */
	public function getErrors()
	{
		if ($this->_validator)
		{
			return $this->_validator->getErrors();
		}
		return [];
	}

	/**
	 *
	 * @return bool
	 * @Ignore
	 */
	public function validate()
	{
		$this->_validator = new \Maslosoft\Mangan\Validator($this);
		return $this->_validator->validate();
	}

}
