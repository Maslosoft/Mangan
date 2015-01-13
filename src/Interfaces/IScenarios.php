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

namespace Maslosoft\Mangan\Interfaces;

/**
 * Use this to provide scenarios
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
interface IScenarios
{

	const Insert = 'insert';
	const Update = 'update';
	const Delete = 'delete';

	/**
	 * Set Scenario
	 * @param string $scenario
	 */
	public function setScenario($scenario);

	/**
	 * Get scenario
	 * @return string Scenario, by default IScenarios::Insert
	 */
	public function getScenario();
}
