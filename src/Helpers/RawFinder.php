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

	public function __construct($model, $em = null)
	{
		parent::__construct($model, $em);
		// Cannot use cursors in raw finder, as it will clash with PkManager
		$this->withCursor(false);
	}

	protected function populateRecord($data)
	{
		return $data;
	}

}
