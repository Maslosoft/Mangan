<?php

/**
 * This software package is licensed under AGPL or Commercial license.
 *
 * @package maslosoft/mangan
 * @licence AGPL or Commercial
 * @copyright Copyright (c) Piotr Masełkowski <pmaselkowski@gmail.com>
 * @copyright Copyright (c) Maslosoft
 * @copyright Copyright (c) Others as mentioned in code
 * @link https://maslosoft.com/mangan/
 */

namespace Maslosoft\Mangan;

use Maslosoft\Addendum\Interfaces\AnnotatedInterface;
use Maslosoft\Mangan\Criteria\ConditionDecorator;
use Maslosoft\Mangan\Criteria\Conditions;
use Maslosoft\Mangan\Interfaces\Criteria\LimitableInterface;
use Maslosoft\Mangan\Interfaces\Criteria\MergeableInterface;
use Maslosoft\Mangan\Interfaces\Criteria\SelectableInterface;
use Maslosoft\Mangan\Interfaces\Criteria\SortableInterface;
use Maslosoft\Mangan\Interfaces\CriteriaInterface;
use Maslosoft\Mangan\Interfaces\SortInterface;
use Maslosoft\Mangan\Traits\Criteria\CursorAwareTrait;
use Maslosoft\Mangan\Traits\Criteria\DecoratableTrait;
use Maslosoft\Mangan\Traits\Criteria\LimitableTrait;
use Maslosoft\Mangan\Traits\Criteria\SelectableTrait;
use Maslosoft\Mangan\Traits\Criteria\SortableTrait;

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
class Criteria implements CriteriaInterface
{

	use CursorAwareTrait,
	  DecoratableTrait,
	  LimitableTrait,
	  SelectableTrait,
	  SortableTrait;

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
	const SortAsc = SortInterface::SortAsc;

	/**
	 * Sort Descending
	 */
	const SortDesc = SortInterface::SortDesc;

	/**
	 * Sort Ascending
	 * @deprecated since version 4.0.7
	 */
	const SORT_ASC = SortInterface::SortAsc;

	/**
	 * Sort Descending
	 * @deprecated since version 4.0.7
	 */
	const SORT_DESC = SortInterface::SortDesc;

	private $_conditions = [];

	/**
	 * Raw conditions array
	 * @var mixed[]
	 */
	private $_rawConds = [];
	private $_workingFields = [];

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
	 * @param mixed|CriteriaInterface|Conditions $criteria
	 * @param AnnotatedInterface|null Model to use for criteria decoration
	 * @since v1.0
	 */
	public function __construct($criteria = null, AnnotatedInterface $model = null)
	{
		$this->setCd(new ConditionDecorator($model));
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
		// NOTE:
		//Scrunitizer: $criteria is of type object<Maslosoft\Mangan\...ria\MergeableInterface>, but the function expects a array|object<Maslosoft\M...aces\CriteriaInterface>.
		// But for now it should be this way to easyli distinguish from Conditions.
		// Future plan: Use CriteriaInterface here, and drop `$criteria instanceof Conditions` if clause. Conditions should implement CriteriaInterface too.
		elseif ($criteria instanceof MergeableInterface)
		{
			$this->mergeWith($criteria);
		}
		elseif ($criteria instanceof Conditions)
		{
			$this->setConditions($criteria);
		}
	}

	/**
	 * Merge with other criteria
	 * - Field list operators will be merged
	 * - Limit and offet will be overriden
	 * - Select fields list will be merged
	 * - Sort fields list will be merged
	 * @param array|CriteriaInterface $criteria
	 * @return CriteriaInterface
	 * @since v1.0
	 */
	public function mergeWith($criteria)
	{
		if (is_array($criteria))
		{
			$criteria = new static($criteria);
		}
		elseif (empty($criteria))
		{
			return $this;
		}

		if ($this instanceof LimitableInterface && $criteria instanceof LimitableInterface && !empty($criteria->getLimit()))
		{
			$this->setLimit($criteria->getLimit());
		}
		if ($this instanceof LimitableInterface && $criteria instanceof LimitableInterface && !empty($criteria->getOffset()))
		{
			$this->setOffset($criteria->getOffset());
		}
		if ($this instanceof SortableInterface && $criteria instanceof SortableInterface && !empty($criteria->getSort()))
		{
			$this->setSort($criteria->getSort());
		}
		if ($this instanceof SelectableInterface && $criteria instanceof SelectableInterface && !empty($criteria->getSelect()))
		{
			$this->select($criteria->getSelect());
		}



		$this->_conditions = $this->_mergeConditions($this->_conditions, $criteria->getConditions());

		return $this;
	}

	private function _mergeConditions($source, $conditions)
	{
		$opTable = array_values(self::$operators);
		foreach ($conditions as $fieldName => $conds)
		{
			if (
					is_array($conds) &&
					count(array_diff(array_keys($conds), $opTable)) == 0
			)
			{
				if (isset($source[$fieldName]) && is_array($source[$fieldName]))
				{
					foreach ($source[$fieldName] as $operator => $value)
					{
						if (!in_array($operator, $opTable))
						{
							unset($source[$fieldName][$operator]);
						}
					}
				}
				else
				{
					$source[$fieldName] = [];
				}

				foreach ($conds as $operator => $value)
				{
					$source[$fieldName][$operator] = $value;
				}
			}
			else
			{
				$source[$fieldName] = $conds;
			}
		}
		return $source;
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
	 * Return query array
	 * @return array query array
	 * @since v1.0
	 */
	public function getConditions()
	{
		$conditions = [];
		foreach ($this->_rawConds as $c)
		{
			$conditions = $this->_makeCond($c[Conditions::FieldName], $c[Conditions::Operator], $c[Conditions::Value], $conditions);
		}
		return $this->_mergeConditions($this->_conditions, $conditions);
	}

	/**
	 * Set conditions
	 * @param array|Conditions $conditions
	 * @return Criteria
	 */
	public function setConditions($conditions)
	{
		if ($conditions instanceof Conditions)
		{
			$this->_conditions = $conditions->get();
			return $this;
		}
		$this->_conditions = $conditions;
		return $this;
	}

	/**
	 * Add condition
	 * If specified field already has a condition, values will be merged
	 * duplicates will be overriden by new values!
	 *
	 * NOTE: Should NOT be part of interface
	 *
	 * @param string $fieldName
	 * @param string $op operator
	 * @param mixed $value
	 * @since v1.0
	 */
	public function addCond($fieldName, $op, $value)
	{
		$this->_rawConds[] = [
			Conditions::FieldName => $fieldName,
			Conditions::Operator => $op,
			Conditions::Value => $value
		];
		return $this;
	}

	/**
	 * @since v1.3.1
	 * @deprecated since version number
	 */
	protected function getWorkingFields()
	{
		return $this->_workingFields;
	}

	/**
	 * @since v1.3.1
	 * @deprecated since version number
	 */
	protected function setWorkingFields(array $select)
	{
		$this->_workingFields = $select;
	}

	/**
	 * Get condition
	 * If specified field already has a condition, values will be merged
	 * duplicates will be overriden by new values!
	 * @see getConditions
	 * @param string $fieldName
	 * @param string $op operator
	 * @param mixed $value
	 * @since v1.0
	 */
	private function _makeCond($fieldName, $op, $value, $conditions = [])
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
				$decorated = $this->getCd()->decorate($fieldName, $val);
				$fieldName = key($decorated);
				$values[] = current($decorated);
			}
			$value = $values;
		}
		else
		{
			$decorated = $this->getCd()->decorate($fieldName, $value);
			$fieldName = key($decorated);
			$value = current($decorated);
		}

		// Apply operators
		$op = self::$operators[$op];

		if ($op == self::$operators['or'])
		{
			if (!isset($conditions[$op]))
			{
				$conditions[$op] = [];
			}
			$conditions[$op][] = [$fieldName => $value];
		}
		else
		{
			if (!isset($conditions[$fieldName]) && $op != self::$operators['equals'])
			{
				$conditions[$fieldName] = [];
			}

			if ($op != self::$operators['equals'])
			{
				if (
						!is_array($conditions[$fieldName]) ||
						count(array_diff(array_keys($conditions[$fieldName]), array_values(self::$operators))) > 0
				)
				{
					$conditions[$fieldName] = [];
				}
				$conditions[$fieldName][$op] = $value;
			}
			else
			{
				$conditions[$fieldName] = $value;
			}
		}
		return $conditions;
	}

}
