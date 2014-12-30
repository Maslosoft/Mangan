<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Maslosoft\Mangan\Options;

use Maslosoft\Mangan\Document;
use Maslosoft\Mangan\Helpers\PropertyMaker;
use Maslosoft\Mangan\Mangan;
use Maslosoft\Mangan\Meta\DocumentTypeMeta;
use Maslosoft\Mangan\Meta\ManganMeta;

/**
 * EntityOptions
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class EntityOptions
{

	use \Maslosoft\Mangan\Traits\Defaults\MongoClientOptions;

	/**
	 * Model instance
	 * @var Document
	 */
	private $_model = null;

	/**
	 *
	 * @var DocumentTypeMeta
	 */
	private $_meta = null;

	/**
	 *
	 * @var Mangan
	 */
	private $_mangan = null;

	/**
	 * Values of this instance
	 * @var mixed[]
	 */
	private $_values = [];
	private $_defaults = [];

	/**
	 *
	 * @var string
	 */
	private $_connectionId = Mangan::DefaultConnectionId;

	public function __construct($model)
	{
		// This is to use get/set
		foreach ($this->_getOptionNames() as $name)
		{
			PropertyMaker::defineProperty($this, $name, $this->_defaults);
		}

		$this->_meta = ManganMeta::create($model)->type();
		$this->_connectionId = $this->_meta->connectionId? : Mangan::DefaultConnectionId;
		$this->_mangan = new Mangan($this->_connectionId);
	}

	public function __get($name)
	{
		if (isset($this->_meta->$name))
		{
			return $this->_meta->$name;
		}
		if (array_key_exists($name, $this->_values))
		{
			return $this->_values[$name]; // We have flag set, return it
		}
		return $this->_mangan->$name;
	}

	public function __set($name, $value)
	{
		$this->_values[$name] = $value;
	}

	public function __unset($name)
	{
		unset($this->_values[$name]);
	}

	public function __isset($name)
	{
		return isset($this->_values[$name]);
	}

	public function getSaveOptions()
	{
		$result = [];
		foreach ($this->_getOptionNames() as $name)
		{
			$result[$name] = $this->$name;
		}
		return $result;
	}

}
