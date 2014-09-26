<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Maslosoft\Mangan\Decorators;

use Maslosoft\Mangan\EmbeddedDocument;

/**
 * This creates i18n fields
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class I18N implements IDecorator
{

	public function get(EmbeddedDocument $document, $name, $value)
	{
		return $value[$document->getLang()];
	}

	public function set(EmbeddedDocument $document, $name, $value)
	{
		return $value[$document->getLang()];
	}

}
