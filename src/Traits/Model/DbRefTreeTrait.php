<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Maslosoft\Mangan\Traits\Model;

/**
 * DbRef Tree Trait
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
trait DbRefTreeTrait
{

	/**
	 * @DbRefArray
	 * @var AnnotatedInterface[]
	 */
	public $children = [];

}
