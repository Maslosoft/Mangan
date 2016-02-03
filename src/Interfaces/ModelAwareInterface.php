<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Maslosoft\Mangan\Interfaces;

use Maslosoft\Addendum\Interfaces\AnnotatedInterface;

/**
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
interface ModelAwareInterface
{

	/**
	 * Get currently used model
	 * @return AnnotatedInterface
	 */
	public function getModel();

	/**
	 * Set model for further use
	 * @param AnnotatedInterface $model
	 */
	public function setModel(AnnotatedInterface $model);
}
