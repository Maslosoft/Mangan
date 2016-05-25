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

use Maslosoft\Mangan\Interfaces\ScenariosInterface;

/**
 * This trait provides implementation of scenarios feature. It works with
 * `ScenarioManager`, but only if class using it
 * also implements `ScenariosInterface`.
 *
 * Use this to enchance models with scenarios, this allows triggering different
 * validators depending on scenario, or to be used with any custom feature.
 *
 *
 * @see ScenariosInterface
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
trait ScenariosTrait
{

	private $_scenario = ScenariosInterface::Insert;

	/**
	 *
	 * @return string
	 * @Ignored
	 */
	public function getScenario()
	{
		return $this->_scenario;
	}

	/**
	 *
	 * @param string $scenario
	 * @Ignored
	 */
	public function setScenario($scenario)
	{
		$this->_scenario = $scenario;
	}

}
