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

namespace Maslosoft\Mangan\Validators\Traits;

/**
 * Use this trait to add validator `on` field.
 *
 * This will allow to use validator only on specified scenarios.
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
trait OnScenario
{

	/**
	 * Scenario on which validator should be used.
	 *
	 * If empty, validator will be used on every scenario.
	 * 
	 * @Sanitizer('None')
	 * @var string|string[]
	 */
	public $on = '';

	/**
	 * Scenario on which validator should **not** be used.
	 *
	 * If empty, validator will be used on every scenario.
	 *
	 * @Sanitizer('None')
	 * @var string|string[]
	 */
	public $except = '';

}
