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

namespace Maslosoft\Mangan\Interfaces\Validators;

/**
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
interface ValidatorProxyInterface
{
	/**
	 * Set validator
	 * @param ValidatorInterface $validator
	 */
	public function setValidator(ValidatorInterface $validator);

	/**
	 * Get validator
	 * @return ValidatorInterface Validator instance
	 */
	public function getValidator();
}
