<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
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
