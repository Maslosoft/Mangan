<?php

/**
 * This software package is licensed under New BSD license.
 *
 * @package maslosoft/mangan
 * @licence New BSD
 * @copyright Copyright (c) Piotr Masełkowski <pmaselkowski@gmail.com>
 * @copyright Copyright (c) Maslosoft
 * @copyright Copyright (c) Others as mentioned in code
 * @link http://maslosoft.com/mangan/
 */

namespace Maslosoft\Mangan\Traits;

use Maslosoft\Mangan\Interfaces\IValidatable;

/**
 * ValidatableTrait
 * @see IValidatable
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
trait ValidatableTrait
{

	/**
	 * Validator instance
	 * @var \Maslosoft\Mangan\Validator
	 */
	private $_validator = null;

	public function getErrors()
	{
		if ($this->_validator)
		{
			return $this->_validator->getErrors();
		}
		return [];
	}

	public function validate()
	{
		$this->_validator = new \Maslosoft\Mangan\Validator($this);
		return $this->_validator->validate();
	}

}
