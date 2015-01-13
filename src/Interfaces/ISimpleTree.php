<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Maslosoft\Mangan\Interfaces;

/**
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
interface ISimpleTree
{
	/**
	 * Move to a new parent
	 * @param string|MongoId $parentId
	 * @param string[]|MongoId[] $order
	 */
	public function moveTo($parentId, $order = []);
}
