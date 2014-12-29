<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Maslosoft\Mangan\Helpers\Decorator;

use Maslosoft\Mangan\Decorators\IDecorator;
use Maslosoft\Mangan\Helpers\Transformator;
use Maslosoft\Mangan\Meta\DocumentPropertyMeta;

/**
 * Decorator
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class Decorator extends Transformator
{

	/**
	 * Read value from database
	 * @param string $name
	 * @param mixed $dbValue
	 */
	public function read($name, &$dbValue)
	{
		$this->getFor($name)->read($this->getModel(), $name, $dbValue);
	}

	/**
	 * Write value into database
	 * @param string $name
	 * @param mixed $dbValue
	 */
	public function write($name, &$dbValue)
	{
		$this->getFor($name)->write($this->getModel(), $name, $dbValue);
	}

	/**
	 *
	 * @param DocumentPropertyMeta $meta
	 * @return IDecorator
	 */
	protected function _getTransformer(DocumentPropertyMeta $meta)
	{
		return Factory::create($meta);
	}

}
