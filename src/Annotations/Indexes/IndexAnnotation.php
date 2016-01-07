<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Maslosoft\Mangan\Annotations\Indexes;

use Exception;
use Maslosoft\Mangan\Meta\ManganPropertyAnnotation;

/**
 * IndexAnnotation
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class IndexAnnotation extends ManganPropertyAnnotation
{

	const Ns = __NAMESPACE__;

	public function init()
	{
		throw new Exception('Not implemented');
	}

}
