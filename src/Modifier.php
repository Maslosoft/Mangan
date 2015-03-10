<?php

/**
 * This software package is licensed under AGPL or Commercial license.
 *
 * @package maslosoft/mangan
 * @licence AGPL or Commercial
 * @copyright Copyright (c) Piotr Masełkowski <pmaselkowski@gmail.com>
 * @copyright Copyright (c) Maslosoft
 * @copyright Copyright (c) Others as mentioned in code
 * @link http://maslosoft.com/mangan/
 */

namespace Maslosoft\Mangan;

/**
 * \Maslosoft\Mangan\Modifier
 *
 * Helper for building MongoDB atomic updates.
 *
 * 1. addCond method
 * $criteriaObject->addCond($fieldName, $operator, $vale); // this will produce fieldName <operator> value
 *
 * For modifiers list {@see \Maslosoft\Mangan\Modifier::$modifiers}
 *
 * @author Ianaré Sévi
 * @author Philippe Gaultier <pgaultier@ibitux.com>
 * @copyright 2011 Ibitux http://www.ibitux.com
 * @method void inc(string $fieldName, mixed $value) inc shorthand
 * @method void set(string $fieldName, mixed $value) set shorthand
 * @method void unset(string $fieldName, mixed $value) unset shorthand
 * @method void push(string $fieldName, mixed $value) push shorthand
 * @method void pushAll(string $fieldName, mixed $value) pushAll shorthand
 * @method void addToSet(string $fieldName, mixed $value) addToSet shorthand
 * @method void pop(string $fieldName, mixed $value) pop shorthand
 * @method void pull(string $fieldName, mixed $value) pull shorthand
 * @method void pullAll(string $fieldName, mixed $value) pullAll shorthand
 * @method void rename(string $fieldName, mixed $value) rename shorthand
 *
 * @since		v1.3.6
 */
class Modifier
{

	/**
	 * @since v1.3.6
	 * @var array $modifiers supported modifiers
	 */
	public static $modifiers = [
		'inc' => '$inc',
		'set' => '$set',
		'unset' => '$unset',
		'push' => '$push',
		'pushAll' => '$pushAll',
		'addToSet' => '$addToSet',
		'pop' => '$pop',
		'pull' => '$pull',
		'pullAll' => '$pullAll',
		'rename' => '$rename',
	];

	/**
	 * @var array
	 */
	private $_fields = [];

	/**
	 * Constructor.
	 *
	 * Modifier sample:
	 *
	 * <PRE>
	 * 'modifier' = array(
	 * 	'fieldName1'=>array('inc' => $incValue),
	 * 	'fieldName2'=>array('set' => $targetValue),
	 * 	'fieldName3'=>array('unset' => 1),
	 * 	'fieldName4'=>array('push' => $pushedValue),
	 * 	'fieldName5'=>array('pushAll' => array($pushedValue1, $pushedValue2)),
	 * 	'fieldName6'=>array('addToSet' => $addedValue),
	 * 	'fieldName7'=>array('pop' => 1),
	 * 	'fieldName8'=>array('pop' => -1),
	 * 	'fieldName9'=>array('pull' => $removedValue),
	 * 	'fieldName10'=>array('pullAll' => array($removedValue1, $removedValue2)),
	 * 	'fieldName11'=>array('rename' => $newFieldName),
	 * );
	 * </PRE>
	 * @param array $modifier basic definition of modifiers
	 * @since v1.3.6
	 */
	public function __construct($modifier = null)
	{
		if (is_array($modifier))
		{
			foreach ($modifier as $fieldName => $rules)
			{
				foreach ($rules as $mod => $value)
				{
					$this->_fields[$fieldName] = [self::$modifiers[$mod] => $value];
				}
			}
		}
		if ($modifier instanceof Modifier)
		{
			$this->mergeWith($modifier);
		}
	}

	public function __call($name, $arguments)
	{
		return $this->addModifier(array_shift($arguments), $name, array_shift($arguments));
	}

	/**
	 * Compute modifier to be able to initiate request.
	 * @return array
	 */
	public function getModifiers()
	{
		$modifier = [];
		foreach ($this->_fields as $fieldName => $rule)
		{
			foreach ($rule as $operator => $value)
			{
				if (isset($modifier[$operator]) && is_array($modifier[$operator]))
				{
					$modifier[$operator] = array_merge($modifier[$operator], [$fieldName => $value]);
				}
				else
				{
					$modifier[$operator] = [$fieldName => $value];
				}
			}
		}
		return $modifier;
	}

	/**
	 * @return array
	 */
	public function getFields()
	{
		return $this->_fields;
	}

	/**
	 * Add a new set of modifiers to current modifiers. If modifiers has already been
	 * added for specific field, they will be overwritten.
	 * @param Modifier $modifier modifier to merge into current object
	 * @return Modifier
	 */
	public function mergeWith($modifier)
	{
		if (is_array($modifier))
		{
			$modifier = new Modifier($modifier);
		}
		if (empty($modifier))
		{
			return $this;
		}

		foreach ($modifier->getFields() as $fieldName => $rule)
		{
			$this->_fields[$fieldName] = $rule;
		}
		return $this;
	}

	/**
	 * Add a new modifier rule to specific field.
	 * @param string $fieldName name of the field we want to update
	 * @param string $modifier  type of the modifier @see \Maslosoft\Mangan\Modifier::$modifiers
	 * @param mixed  $value     value used by the modifier
	 * @return Modifier
	 */
	public function addModifier($fieldName, $modifier, $value)
	{
		$this->_fields[$fieldName] = [self::$modifiers[$modifier] => $value];
		return $this;
	}

	/**
	 * Check if we have modifiers to apply.
	 * @return boolean
	 */
	public function canApply()
	{
		if (count($this->_fields) > 0)
		{
			return true;
		}
		else
		{
			return false;
		}
	}

}
