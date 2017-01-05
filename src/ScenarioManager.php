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

namespace Maslosoft\Mangan;

use Maslosoft\Addendum\Interfaces\AnnotatedInterface;
use Maslosoft\Mangan\Interfaces\ScenariosInterface;

/**
 * ScenarioManager
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class ScenarioManager
{

	/**
	 * Set Scenario
	 * @see ScenariosInterface
	 * @param ScenariosInterface|AnnotatedInterface $model
	 * @param string $scenario
	 */
	public static function setScenario(AnnotatedInterface $model, $scenario)
	{
		if ($model instanceof ScenariosInterface)
		{
			$model->setScenario($scenario);
		}
	}

	/**
	 * Get scenario
	 * @see ScenariosInterface
	 * @param ScenariosInterface|AnnotatedInterface $model
	 * @return string Scenario, by default ScenariosInterface::Insert
	 */
	public static function getScenario(AnnotatedInterface $model)
	{
		if ($model instanceof ScenariosInterface)
		{
			return $model->getScenario();
		}
		return ScenariosInterface::Insert;
	}

}
