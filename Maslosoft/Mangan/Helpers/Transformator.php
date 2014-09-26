<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Maslosoft\Mangan\Helpers;

use Exception;
use Maslosoft\Addendum\Collections\Meta;
use Maslosoft\Addendum\Collections\MetaProperty;
use Maslosoft\Mangan\EmbeddedDocument;
use Maslosoft\Mangan\Sanitizers\ISanitizer;

/**
 * Transformator
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
abstract class Transformator
{

	/**
	 * Metadata for document
	 * @var Meta
	 */
	private $_meta = null;

	/**
	 * Hash map of sanitizers
	 * @var ISanitizer[]
	 */
	private $_sanitizers = [];

	public function __construct(EmbeddedDocument $document)
	{
		$this->_meta = $document->meta;
	}

	public function __get($name)
	{
		if (!array_key_exists($name, $this->_sanitizers))
		{
			$this->_sanitizers[$name] = $this->_getTransformer($this->_meta->$name);
		}
		return $this->_sanitizers[$name];
	}

	public function __set($name, $value)
	{
		throw new Exception(sprintf('Cannot set field `%s` of `%s` (tried to set with value of type `%s`)', $name, __CLASS__, gettype($value)));
	}

	abstract protected function _getTransformer(MetaProperty $meta);
}
