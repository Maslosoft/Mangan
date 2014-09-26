<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Maslosoft\Mangan\Helpers\Sanitizer;

use Maslosoft\Addendum\Collections\MetaProperty;
use Maslosoft\Mangan\Helpers\Transformator;

/**
 * Sanitizer
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class Sanitizer extends Transformator
{

	protected function _getTransformer(MetaProperty $meta)
	{
		return Factory::create($meta);
	}

}
