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

namespace Maslosoft\Mangan\Interfaces;

/**
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
interface IValidatorProxy
{
	/**
	 * Set validator
	 * @param IValidator $validator
	 */
	public function setValidator(IValidator $validator);

	/**
	 * Get validator
	 * @return IValidator Validator instance
	 */
	public function getValidator();
}
