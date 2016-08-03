<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Maslosoft\Mangan\Traits;

use Maslosoft\Mangan\Interfaces\NameAwareInterface;

/**
 * Basic implementation of Name Aware Interface
 *
 * @see NameAwareInterface
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
trait NameAwareTrait
{

	private $name = '';

	public function getName()
	{
		return $this->name;
	}

	public function setName($name)
	{
		$this->name = $name;
		return $this;
	}

}
