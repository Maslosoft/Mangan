<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Maslosoft\Mangan\Interfaces;

use Maslosoft\Mangan\Mangan;

/**
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
interface ManganAwareInterface
{

	/**
	 * Set mangan instance
	 * @param Mangan $mangan
	 */
	public function setMangan(Mangan $mangan);
}
