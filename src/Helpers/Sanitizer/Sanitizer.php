<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Maslosoft\Mangan\Helpers\Sanitizer;

use Maslosoft\Mangan\Helpers\Transformator;
use Maslosoft\Mangan\Meta\DocumentPropertyMeta;

/**
 * Sanitizer
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class Sanitizer extends Transformator
{

	public function read($name, $dbValue)
	{
		return $this->getFor($name)->read($this->getModel(), $dbValue);
	}

	public function write($name, $phpValue)
	{
		return $this->getFor($name)->write($this->getModel(), $phpValue);
	}

	protected function _getTransformer(DocumentPropertyMeta $meta)
	{
		return Factory::create($meta);
	}

}
