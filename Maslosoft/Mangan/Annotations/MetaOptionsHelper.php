<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Maslosoft\Mangan\Annotations;

/**
 * MetaOptionsHelper
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class MetaOptionsHelper
{

	const Ns = __NAMESPACE__;

	public function __construct()
	{
		throw new Exception(sprintf('Do not use %s, this is only heleper class for meta options', __CLASS__));
	}

}
