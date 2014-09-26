<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Maslosoft\Mangan\Decorators;

use Maslosoft\Mangan\EmbeddedDocument;

/**
 * This should modify fields bahavior
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
interface IDecorator
{

	/**
	 * This will be called when getting value.
	 * This should return end user value.
	 * @param EmbeddedDocument $document Document which will be decorated
	 * @param string $name Field name
	 * @param mixed $value
	 */
	public function get(EmbeddedDocument $document, $name, $value);

	/**
	 * This will be called when setting value.
	 * This should return db acceptable value
	 * @param EmbeddedDocument $document Document which will be decorated
	 * @param string $name Field name
	 * @param mixed $value
	 */
	public function set(EmbeddedDocument $document, $name, $value);
}
