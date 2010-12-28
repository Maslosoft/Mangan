<?php
/**
 * EMongoSoftDocument.php
 *
 * PHP version 5.2+
 *
 * @author		Dariusz Górecki <darek.krk@gmail.com>
 * @author		Invenzzia Group, open-source division of CleverIT company http://www.invenzzia.org
 * @copyright	2010 CleverIT http://www.cleverit.com.pl
 * @license		http://www.yiiframework.com/license/ BSD license
 * @version		1.3
 * @category	ext
 * @package		ext.YiiMongoDbSuite
 *
 */

/**
 * EmongoSoftDocument cass
 */
abstract class EMongoSoftDocument extends EMongoDocument
{
	/**
	 * Array that holds initialized soft attributes
	 * @var array $softAttributes
	 */
	protected $softAttributes = array();

	private $_attributeNames = array();

	public function __get($name)
	{
		if(array_key_exists($name, $this->softAttributes)) // Use of array_key_exists is mandatory !!!
		{
			return $this->softAttributes[$name];
		}
		else
			return parent::__get($name);
	}

	public function __set($name, $value)
	{
		if(array_key_exists($name, $this->softAttributes)) // Use of array_key_exists is mandatory !!!
		{
			$this->softAttributes[$name] = $value;
		}
		else
			parent::__set($name, $value);
	}

	public function __isset($name)
	{
		if(array_key_exists($name, $this->softAttributes)) // Use of array_key_exists is mandatory !!!
			return true;
		else
			return parent::__isset($name);
	}

	public function __unset($name)
	{
		if(array_key_exists($name, $this->softAttributes)) // Use of array_key_exists is mandatory !!!
		{
			unset($this->softAttributes[$name]);
			$names = array_flip($this->_attributeNames);
			unset($this->_attributeNames[$names[$name]]);
		}
		else
			parent::__unset($name);
	}

	public function initSoftAttribute($name)
	{
		$this->softAttributes[$name] = null;
		$this->_attributeNames[] = $name;
	}

	public function initSoftAttributes($attributes)
	{
		foreach($attributes as $name)
			$this->initSoftAttribute($name);
	}

	public function attributeNames()
	{
		return array_merge($this->_attributeNames, parent::attributeNames());
	}

	protected function instantiate($attributes)
	{
		$class=get_class($this);
		$model=new $class(null);
		$model->initEmbeddedDocuments();

		$model->initSoftAttributes(
			array_diff(
				array_keys($attributes),
				parent::attributeNames()
			)
		);

		$model->setAttributes($attributes, false);
		return $model;
	}

	/**
	 * This method does the actual convertion to an array
	 * Does not fire any events
	 * @return array an associative array of the contents of this object
	 */
	protected function _toArray()
	{
		$arr = parent::_toArray();
		foreach($this->softAttributes as $key => $value)
			$arr[$key]=$value;
		return $arr;
	}

	public function getSoftAttributeNames()
	{
		return $this->_attributeNames;
	}
}