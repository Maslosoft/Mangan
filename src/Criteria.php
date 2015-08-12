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

use Maslosoft\Addendum\Interfaces\AnnotatedInterface;
use Maslosoft\Mangan\Criteria\ConditionDecorator;

/**
 * Criteria
 *
 * This class is a helper for building MongoDB query arrays, it support three syntaxes for adding conditions:
 *
 * 1. 'equals' syntax:
 * 	$criteriaObject->fieldName = $value; // this will produce fieldName == value query
 * 2. fieldName call syntax
 * 	$criteriaObject->fieldName($operator, $value); // this will produce fieldName <operator> value
 * 3. addCond method
 * 	$criteriaObject->addCond($fieldName, $operator, $vale); // this will produce fieldName <operator> value
 *
 * For operators list {@see Criteria::$operators}
 *
 * @author Ianaré Sévi
 * @author Dariusz Górecki <darek.krk@gmail.com>
 * @author Invenzzia Group, open-source division of CleverIT company http://www.invenzzia.org
 * @copyright 2011 CleverIT http://www.cleverit.com.pl
 * @license New BSD license
 */
class Criteria
{

	/**
	 * @since v1.0
	 * @var array $operators supported operators lists
	 */
	public static $operators = [

		// Comparison
		// Matches values that are equal to a specified value.
		'eq' => '$eq',
		'equals' => '$eq',
		'==' => '$eq',
		// Matches values that are greater than a specified value.
		'gt' => '$gt',
		'greater' => '$gt',
		'>' => '$gt',
		// Matches values that are greater than or equal to a specified value.
		'gte' => '$gte',
		'greatereq' => '$gte',
		'>=' => '$gte',
		// Matches values that are less than a specified value.
		'lt' => '$lt',
		'less' => '$lt',
		'<' => '$lt',
		// Matches values that are less than or equal to a specified value.
		'lte' => '$lte',
		'lesseq' => '$lte',
		'<=' => '$lte',
		// Matches all values that are not equal to a specified value.
		'ne' => '$ne',
		'noteq' => '$ne',
		'!=' => '$ne',
		'<>' => '$ne',
		// Matches any of the values specified in an array.
		'in' => '$in',
		// Matches none of the values specified in an array.
		'notin' => '$nin',
		// Logical
		// Joins query clauses with a logical OR returns all documents that match the conditions of either clause.
		'or' => '$or',
		// Joins query clauses with a logical AND returns all documents that match the conditions of both clauses.
		'and' => '$and',
		// Inverts the effect of a query expression and returns documents that do not match the query expression.
		'not' => '$not',
		// Joins query clauses with a logical NOR returns all documents that fail to match both clauses.
		'nor' => '$nor',
		// Element
		// Matches documents that have the specified field.
		'exists' => '$exists',
		'notexists' => '$exists',
		// Selects documents if a field is of the specified type.
		'type' => '$type',
		// Evaluation
		// Performs a modulo operation on the value of a field and selects documents with a specified result.
		'mod' => '$mod',
		'%' => '$mod',
		// Selects documents where values match a specified regular expression.
		'regex' => '$regex',
		// Performs text search.
		'text' => '$text',
		// Matches documents that satisfy a JavaScript expression.
		'where' => '$where',
		// Geospatial
		// Selects geometries within a bounding GeoJSON geometry. The `2dsphere` and `2d` indexes support $geoWithin.
		'geoWithin' => '$geoWithin',
		// Selects geometries that intersect with a GeoJSON geometry. The `2dsphere` index supports $geoIntersects.
		'geoIntersects' => '$geoIntersects',
		// Returns geospatial objects in proximity to a point. Requires a geospatial index. The `2dsphere` and `2d` indexes support $near.
		'near' => '$near',
		// Returns geospatial objects in proximity to a point on a sphere. Requires a geospatial index. The `2dsphere` and `2d` indexes support $nearSphere.
		'nearSphere' => '$nearSphere',
		// Array
		// Matches arrays that contain all elements specified in the query.
		'all' => '$all',
		// Selects documents if element in the array field matches all the specified $elemMatch conditions.
		'elemmatch' => '$elemMatch',
		// Selects documents if the array field is a specified size.
		'size' => '$size',
		// Comments
		'comment' => '$comment'
	];

	/**
	 * Sort Ascending
	 */
	const SortAsc = 1;

	/**
	 * Sort Descending
	 */
	const SortDesc = -1;

	private $_select = [];
	private $_limit = null;
	private $_offset = null;
	private $_conditions = [];
	private $_sort = [];
	private $_workingFields = [];
	private $_useCursor = null;

	/**
	 * Condition decorator
	 * @var ConditionDecorator
	 */
	private $cd = null;

	/**
	 * Constructor
	 * Example criteria:
	 *
	 * <PRE>
	 * 'criteria' = array(
	 * 	'conditions'=>array(
	 * 		'fieldName1'=>array('greater' => 0),
	 * 		'fieldName2'=>array('>=' => 10),
	 * 		'fieldName3'=>array('<' => 10),
	 * 		'fieldName4'=>array('lessEq' => 10),
	 * 		'fieldName5'=>array('notEq' => 10),
	 * 		'fieldName6'=>array('in' => array(10, 9)),
	 * 		'fieldName7'=>array('notIn' => array(10, 9)),
	 * 		'fieldName8'=>array('all' => array(10, 9)),
	 * 		'fieldName9'=>array('size' => 10),
	 * 		'fieldName10'=>array('exists'),
	 * 		'fieldName11'=>array('notExists'),
	 * 		'fieldName12'=>array('mod' => array(10, 9)),
	 * 		'fieldName13'=>array('==' => 1)
	 * 	),
	 * 	'select'=>array('fieldName', 'fieldName2'),
	 * 	'limit'=>10,
	 *  'offset'=>20,
	 *  'sort'=>array('fieldName1'=>Criteria::SortAsc, 'fieldName2'=>Criteria::SortDesc),
	 * );
	 * </PRE>
	 * @param mixed $criteria
	 * @param AnnotatedInterface|null Model to use for criteria decoration
	 * @since v1.0
	 */
	public function __construct($criteria = null, AnnotatedInterface $model = null)
	{
		$this->cd = new ConditionDecorator($model);
		if (is_array($criteria))
		{
			if (isset($criteria['conditions']))
				foreach ($criteria['conditions'] as $fieldName => $conditions)
				{
					$fieldNameArray = explode('.', $fieldName);
					if (count($fieldNameArray) === 1)
					{
						$fieldName = array_shift($fieldNameArray);
					}
					else
					{
						$fieldName = array_pop($fieldNameArray);
					}

					foreach ($conditions as $operator => $value)
					{
						$this->setWorkingFields($fieldNameArray);
						$operator = strtolower($operator);
						$this->addCond($fieldName, $operator, $value);
					}
				}

			if (isset($criteria['select']))
			{
				$this->select($criteria['select']);
			}
			if (isset($criteria['limit']))
			{
				$this->limit($criteria['limit']);
			}
			if (isset($criteria['offset']))
			{
				$this->offset($criteria['offset']);
			}
			if (isset($criteria['sort']))
			{
				$this->setSort($criteria['sort']);
			}
			if (isset($criteria['useCursor']))
			{
				$this->setUseCursor($criteria['useCursor']);
			}
		}
		elseif ($criteria instanceof Criteria)
		{
			$this->mergeWith($criteria);
		}
	}

	/**
	 * Merge with other criteria
	 * - Field list operators will be merged
	 * - Limit and offet will be overriden
	 * - Select fields list will be merged
	 * - Sort fields list will be merged
	 * @param array|Criteria $criteria
	 * @since v1.0
	 */
	public function mergeWith($criteria)
	{
		if (is_array($criteria))
		{
			$criteria = new Criteria($criteria);
		}
		else if (empty($criteria))
		{
			return $this;
		}

		$opTable = array_values(self::$operators);

		foreach ($criteria->_conditions as $fieldName => $conds)
		{
			if (
					is_array($conds) &&
					count(array_diff(array_keys($conds), $opTable)) == 0
			)
			{
				if (isset($this->_conditions[$fieldName]) && is_array($this->_conditions[$fieldName]))
				{
					foreach ($this->_conditions[$fieldName] as $operator => $value)
					{
						if (!in_array($operator, $opTable))
						{
							unset($this->_conditions[$fieldName][$operator]);
						}
					}
				}
				else
				{
					$this->_conditions[$fieldName] = [];
				}

				foreach ($conds as $operator => $value)
				{
					$this->_conditions[$fieldName][$operator] = $value;
				}
			}
			else
			{
				$this->_conditions[$fieldName] = $conds;
			}
		}

		if (!empty($criteria->_limit))
		{
			$this->_limit = $criteria->_limit;
		}
		if (!empty($criteria->_offset))
		{
			$this->_offset = $criteria->_offset;
		}
		if (!empty($criteria->_sort))
		{
			$this->_sort = array_merge($this->_sort, $criteria->_sort);
		}
		if (!empty($criteria->_select))
		{
			$this->_select = array_merge($this->_select, $criteria->_select);
		}

		return $this;
	}

	/**
	 * If we have operator add it otherwise call parent implementation
	 * @since v1.0
	 */
	public function __call($fieldName, $parameters)
	{
		if (isset($parameters[0]))
		{
			$operatorName = strtolower($parameters[0]);
		}
		if (array_key_exists(1, $parameters))
		{
			$value = $parameters[1];
		}
		if (is_numeric($operatorName))
		{
			$operatorName = strtolower(trim($value));
			$value = (strtolower(trim($value)) === 'exists') ? true : false;
		}

		if (in_array($operatorName, array_keys(self::$operators)))
		{
			array_push($this->_workingFields, $fieldName);
			$fieldName = implode('.', $this->_workingFields);
			$this->_workingFields = [];
			switch ($operatorName)
			{
				case 'exists':
					$this->addCond($fieldName, $operatorName, true);
					break;
				case 'notexists':
					$this->addCond($fieldName, $operatorName, false);
					break;
				default:
					$this->addCond($fieldName, $operatorName, $value);
			}
			return $this;
		}
	}

	/**
	 * @since v1.0.2
	 */
	public function __get($name)
	{
		array_push($this->_workingFields, $name);
		return $this;
	}

	/**
	 * @since v1.0.2
	 */
	public function __set($name, $value)
	{
		array_push($this->_workingFields, $name);
		$fieldList = implode('.', $this->_workingFields);
		$this->_workingFields = [];
		$this->addCond($fieldList, '==', $value);
	}

	/**
	 * Decorate and sanitize criteria with provided model.

	 * @param AnnotatedInterface $model Model to use for decorators and sanitizer when creating conditions. If null no decorators will be used. If model is provided it's sanitizers and decorators will be used.
	 * @return Criteria
	 */
	public function decorateWith($model)
	{
		$this->cd = new ConditionDecorator($model);
		return $this;
	}

	/**
	 * Return query array
	 * @return array query array
	 * @since v1.0
	 */
	public function getConditions()
	{
		return $this->_conditions;
	}

	/**
	 * @since v1.0
	 */
	public function setConditions(array $conditions)
	{
		$this->_conditions = $conditions;
	}

	/**
	 * @since v1.0
	 */
	public function getLimit()
	{
		return $this->_limit;
	}

	/**
	 * @since v1.0
	 */
	public function setLimit($limit)
	{
		$this->limit($limit);
	}

	/**
	 * @since v1.0
	 */
	public function getOffset()
	{
		return $this->_offset;
	}

	/**
	 * @since v1.0
	 */
	public function setOffset($offset)
	{
		$this->offset($offset);
	}

	/**
	 * @since v1.0
	 */
	public function getSort()
	{
		return $this->_sort;
	}

	/**
	 * Set sorting of results. Use model field names as keys and Criteria's sort consntants.
	 *
	 * Afields will be automatically decorated according to model.
	 * For instance, when sorting on i18n field simply use field name, without language prefix.
	 *
	 * Example:
	 * ```php
	 * $criteria = new Criteria();
	 * $sort = [
	 * 		'title' => Criteria::SortAsc
	 * ];
	 * $criteria->setSort();
	 * ```
	 * @since v1.0
	 */
	public function setSort(array $sort)
	{
		foreach ($sort as $fieldName => $order)
		{
			$decorated = $this->cd->decorate($fieldName);
			$this->_sort[key($decorated)] = $order;
		}
	}

	/**
	 * @since v1.3.7
	 */
	public function getUseCursor()
	{
		return $this->_useCursor;
	}

	/**
	 * @since v1.3.7
	 */
	public function setUseCursor($useCursor)
	{
		$this->_useCursor = $useCursor;
	}

	/**
	 * Return selected fields
	 * @return bool[] Fields used for select
	 * @since v1.3.1
	 */
	public function getSelect()
	{
		return $this->_select;
	}

	/**
	 * @since v1.3.1
	 */
	public function setSelect(array $select)
	{
		$this->_select = [];
		// Convert the select array to field=>true/false format
		foreach ($select as $key => $value)
		{
			if (is_int($key))
			{
				$this->_select[$value] = true;
			}
			else
			{
				$this->_select[$key] = $value;
			}
		}
	}

	/**
	 * @since v1.3.1
	 */
	public function getWorkingFields()
	{
		return $this->_workingFields;
	}

	/**
	 * @since v1.3.1
	 */
	public function setWorkingFields(array $select)
	{
		$this->_workingFields = $select;
	}

	/**
	 * List of fields to get from DB
	 * Multiple calls to this method will merge all given fields
	 *
	 * @param array $fieldList list of fields to select
	 * @since v1.0
	 */
	public function select(array $fieldList = null)
	{
		if ($fieldList !== null)
		{
			$this->setSelect(array_merge($this->_select, $fieldList));
		}
		return $this;
	}

	/**
	 * Set linit
	 * Multiple calls will overrride previous value of limit
	 *
	 * @param integer $limit limit
	 * @since v1.0
	 */
	public function limit($limit)
	{
		$this->_limit = intval($limit);
		return $this;
	}

	/**
	 * Set offset
	 * Multiple calls will override previous value
	 *
	 * @param integer $offset offset
	 * @since v1.0
	 */
	public function offset($offset)
	{
		$this->_offset = intval($offset);
		return $this;
	}

	/**
	 * Add sorting, avaliabe orders are: Criteria::SortAsc and Criteria::SortDesc
	 * Each call will be groupped with previous calls
	 * @param string $fieldName
	 * @param integer $order
	 * @return Criteria
	 * @since v1.0
	 */
	public function sort($fieldName, $order)
	{
		$decorated = $this->cd->decorate($fieldName);
		$this->_sort[key($decorated)] = intval($order);
		return $this;
	}

	/**
	 * Add condition
	 * If specified field already has a condition, values will be merged
	 * duplicates will be overriden by new values!
	 * @param string $fieldName
	 * @param string $op operator
	 * @param mixed $value
	 * @since v1.0
	 */
	public function addCond($fieldName, $op, $value)
	{
		// For array values
		$arrayOperators = [
			'or',
			'in',
			'notin'
		];
		if (in_array($op, $arrayOperators))
		{
			// Ensure array
			if (!is_array($value))
			{
				$value = [$value];
			}

			// Decorate each value
			$values = [];
			foreach ($value as $val)
			{
				$decorated = $this->cd->decorate($fieldName, $val);
				$fieldName = key($decorated);
				$values[] = current($decorated);
			}
			$value = $values;
		}
		else
		{
			$decorated = $this->cd->decorate($fieldName, $value);
			$fieldName = key($decorated);
			$value = current($decorated);
		}

		// Apply operators
		$op = self::$operators[$op];

		if ($op == self::$operators['or'])
		{
			if (!isset($this->_conditions[$op]))
			{
				$this->_conditions[$op] = [];
			}
			$this->_conditions[$op][] = [$fieldName => $value];
		}
		else
		{
			if (!isset($this->_conditions[$fieldName]) && $op != self::$operators['equals'])
			{
				$this->_conditions[$fieldName] = [];
			}

			if ($op != self::$operators['equals'])
			{
				if (
						!is_array($this->_conditions[$fieldName]) ||
						count(array_diff(array_keys($this->_conditions[$fieldName]), array_values(self::$operators))) > 0
				)
				{
					$this->_conditions[$fieldName] = [];
				}
				$this->_conditions[$fieldName][$op] = $value;
			}
			else
			{
				$this->_conditions[$fieldName] = $value;
			}
		}
		return $this;
	}

}
