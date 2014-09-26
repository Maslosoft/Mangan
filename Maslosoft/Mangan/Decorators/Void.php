<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Maslosoft\Mangan\Decorators;

use Maslosoft\Mangan\EmbeddedDocument;

/**
 * Void
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class Void implements IDecorator
{

	public function get(EmbeddedDocument $document, $name, $value)
	{
		
	}

	public function set(EmbeddedDocument $document, $name, $value)
	{

	}

}
