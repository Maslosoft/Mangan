<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Maslosoft\Mangan\Interfaces;

/**
 * Implement this interface to allow dynamic/callable collection names in documents
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
interface IWithCollectionName
{

	/**
	 * This method must return collection name for use with this model
	 * this must be implemented in child classes
	 *
	 * this is read-only defined only at class define
	 * if you want to set different collection during run-time
	 * use {@see setCollection()}.
	 * @return string collection name
	 * @since v1.0
	 */
	public function getCollectionName();
}
