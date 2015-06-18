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

use Maslosoft\Mangan\Interfaces\IScenarios;

/**
 * ScenariosTrait
 * @see IScenarios
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
trait ScenariosTrait
{

	private $_scenario = IScenarios::Insert;

	/**
	 *
	 * @return string
	 * @Ignore
	 */
	public function getScenario()
	{
		return $this->_scenario;
	}

	/**
	 *
	 * @param string $scenario
	 * @Ignore
	 */
	public function setScenario($scenario)
	{
		$this->_scenario = $scenario;
	}

}
