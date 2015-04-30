<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Maslosoft\Mangan\Helpers;

use Maslosoft\Mangan\Finder;

/**
 * Finder variant which returns raw arrays.
 * For internal or special cases use.
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class RawFinder extends Finder
{

	protected function populateRecord($data)
	{
		return $data;
	}

}
