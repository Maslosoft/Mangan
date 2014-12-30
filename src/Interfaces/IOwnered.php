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
interface IOwnered
{

	/**
	 * Set class owner
	 * @param IModel $owner
	 */
	public function getOwner($owner);

	/**
	 * Get class owner
	 * @return IModel Owner
	 */
	public function setOwner();

	/**
	 * Get document root
	 * @return IModel Root document
	 */
	public function getRoot();
}
