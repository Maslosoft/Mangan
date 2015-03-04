<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Maslosoft\Mangan\Criteria\Conditions;

use Maslosoft\Addendum\Interfaces\IAnnotated;

/**
 * Conditions
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class Conditions
{

	public function __construct()
	{
		;
	}

	public function add()
	{
		
	}

	public function addOr()
	{
		return $this;
	}

	public function addAnd()
	{
		return $this;
	}


	public function fromArray($conditions)
	{
		
	}

	public function get()
	{
		
	}
}
