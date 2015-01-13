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

namespace Maslosoft\Mangan;

use Maslosoft\Mangan\Interfaces\IModel;
use Maslosoft\Mangan\Interfaces\IScenarios;

/**
 * ScenarioManager
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class ScenarioManager
{

	/**
	 * Set Scenario
	 * @param IScenarios|IModel|object $model
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
	 * @param IScenarios|IModel|object $model
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
