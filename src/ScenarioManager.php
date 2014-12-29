<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Maslosoft\Mangan;

use Maslosoft\Mangan\Inrefaces\IScenarios;

/**
 * ScenarioManager
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class ScenarioManager
{

	/**
	 * Set Scenario
	 * @param IScenarios $model
	 * @param string $scenario
	 */
	public static function setScenario($model, $scenario)
	{
		if ($model instanceof IScenarios)
		{
			$model->setScenario($scenario);
		}
	}

	/**
	 * Get scenario
	 * @param IScenarios $model
	 * @return string Scenario, by default IScenarios::Insert
	 */
	public static function getScenario($model)
	{
		if ($model instanceof IScenarios)
		{
			return $model->getScenario();
		}
		return self::Insert;
	}

}
