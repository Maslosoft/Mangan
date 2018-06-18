<?php
/**
 * Created by PhpStorm.
 * User: peter
 * Date: 18.06.18
 * Time: 22:02
 */

namespace Maslosoft\Mangan\Traits;


use Maslosoft\Mangan\AspectManager;
use Maslosoft\Mangan\Helpers\CompositionIterator;
use Maslosoft\Mangan\Interfaces\AspectsInterface;

/**
 * Use this trait for easy implementation of
 * AspectsInterface
 * @see AspectManager
 * @see AspectsInterface
 * @package Maslosoft\Mangan\Traits
 */
trait AspectsTrait
{
	private $aspects = [];

	/**
	 * Add aspect
	 * @param string $aspect
	 */
	public function addAspect($aspect)
	{
		$this->aspects[(string)$aspect] = true;
		$it = new CompositionIterator($this);
		$it->ofType(AspectsInterface::class);
		foreach($it as $subDocument)
		{
			AspectManager::addAspect($subDocument, $aspect);
		}
	}

	/**
	 * Remove aspect
	 * @param string $aspect
	 */
	public function removeAspect($aspect)
	{
		unset($this->aspects[(string)$aspect]);
		$it = new CompositionIterator($this);
		$it->ofType(AspectsInterface::class);
		foreach($it as $subDocument)
		{
			AspectManager::removeAspect($subDocument, $aspect);
		}
	}

	/**
	 * Check if has aspect
	 * @param string $aspect
	 * @return bool
	 */
	public function hasAspect($aspect)
	{
		return isset($this->aspects[(string)$aspect]);
	}

}