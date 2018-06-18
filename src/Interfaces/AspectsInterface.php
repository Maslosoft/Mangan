<?php
/**
 * Created by PhpStorm.
 * User: peter
 * Date: 18.06.18
 * Time: 22:01
 */

namespace Maslosoft\Mangan\Interfaces;


interface AspectsInterface
{
	/**
	 * Add aspect
	 * @param string $aspect
	 */
	public function addAspect($aspect);

	/**
	 * Remove aspect
	 * @param string $aspect
	 */
	public function removeAspect($aspect);

	/**
	 * Check whether current model has aspect
	 * @param $aspect
	 * @return string
	 */
	public function hasAspect($aspect);
}