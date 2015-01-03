<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
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

	public function getScenario()
	{
		return $this->_scenario;
	}

	public function setScenario($scenario)
	{
		$this->_scenario = $scenario;
	}

}
