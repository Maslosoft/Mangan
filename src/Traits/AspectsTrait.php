<?php
/**
 * Created by PhpStorm.
 * User: peter
 * Date: 18.06.18
 * Time: 22:02
 */

namespace Maslosoft\Mangan\Traits;


use Maslosoft\Mangan\Interfaces\AspectsInterface;

class AspectsTrait implements AspectsInterface
{
	private $aspects = [];

	public function addAspect($aspect)
	{
		$this->aspects[(string)$aspect] = true;
	}

	public function removeAspect($aspect)
	{
		unset($this->aspects[(string)$aspect]);
	}

	public function hasAspect($aspect)
	{
		return isset($this->aspects[(string)$aspect]);
	}

}