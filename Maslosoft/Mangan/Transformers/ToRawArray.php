<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Maslosoft\Mangan\Transformers;

use ArrayAccess;
use Maslosoft\Addendum\Collections\Meta;
use Maslosoft\Mangan\Document;
use Maslosoft\Mangan\Meta\ManganMeta;
use MongoId;
use Yii;

/**
 * This transforms document into mongodb insertable array
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class ToRawArray implements ArrayAccess
{

	/**
	 * Model instance
	 * @var Document
	 */
	public $model;

	/**
	 *
	 * @var Meta
	 */
	public $meta = null;

	/**
	 * Current model class name
	 * @var string
	 */
	private $_class = '';
	private $_values = null;

	public function __construct($model)
	{
		$this->model = $model;
		$this->meta = ManganMeta::create($model);
		$this->_class = get_class($model);
	}

	/**
	 * Returns the given object as an associative array
	 * Fires beforeToArray and afterToArray events
	 * @return array an associative array of the contents of this object
	 * @since v1.0.8
	 */
	public function toArray($associative = true)
	{
		if (true)
		{
			return $this->_toArray($associative);
		}
		else
		{
			return [];
		}
	}

	/**
	 * This method does the actual convertion to an array
	 * Does not fire any events
	 * @return array an associative array of the contents of this object
	 * @since v1.3.4
	 */
	protected function _toArray($associative = true)
	{
		$arr = [];
		foreach ($this->meta->fields() as $name => $field)
		{
			// Type check is required here, so by default attribute is persistent
			/**
			 * TODO This should be implemented as decorators
			 */
			if ($field->persistent !== false)
			{
				if ($field->i18n)
				{
					foreach (Yii::app()->languages as $lang => $langName)
					{
						$arr[$name][$lang] = $this->_attributeToArray($field, $name, $lang, $associative);
					}
				}
				else
				{
					$arr[$name] = $this->_attributeToArray($field, $name, null, $associative);
				}
			}
		}
		$arr['_class'] = $this->_class;
		return $arr;
	}

	protected function _attributeToArray($field, $name, $lang, $associative = true)
	{
		if ($field->embedded)
		{
			if ($field->embeddedArray)
			{
				$value = [];
				foreach ((array) $this->model->getAttribute($name, $lang) as $key => $docValue)
				{
					if (!is_object($docValue))
					{
						continue;
					}
					if (!$docValue->_key)
					{
						$docValue->_key = (string) new MongoId();
					}
					$key = $docValue->_key;
					$value[$key] = (array) new ToRawArray($docValue);
				}
				if (!$associative)
				{
					$value = array_values($value);
				}
			}
			else
			{
				$value = $this->model->getAttribute($name, $lang);
				if (is_object($value))
				{
					$value = (array) new ToRawArray($value);
				}
				else
				{
					$value = $field->default;
				}
			}
		}
		else
		{
			$value = $this->model->getAttribute($name, $lang);
		}
		return $value;
	}

// <editor-fold defaultstate="collapsed" desc="ArrayAccess implementation">

	private function _initArrayAccess()
	{
		if (null === $this->_values)
		{
			$this->_values = $this->toArray();
		}
	}

	public function offsetExists($offset)
	{
		$this->_initArrayAccess();
		return array_key_exists($offset, $this->_values);
	}

	public function offsetGet($offset)
	{
		$this->_initArrayAccess();
		return $this->_values[$offset];
	}

	public function offsetSet($offset, $value)
	{
		$this->_initArrayAccess();
		$this->_values[$offset] = $value;
	}

	public function offsetUnset($offset)
	{
		$this->_initArrayAccess();
		unset($this->_values[$offset]);
	}

// </editor-fold>
}
