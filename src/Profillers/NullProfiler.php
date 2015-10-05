<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Maslosoft\Mangan\Profillers;

use Maslosoft\Mangan\Interfaces\ProfilerInterface;
use MongoCursor;

/**
 * NullProfiller
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class NullProfiler implements ProfilerInterface
{

	public function profile($data)
	{
		// Do nothing
	}

	public function cursor(MongoCursor $cursor)
	{
		// Do nothing
	}

}
