<?php

/**
 * This software package is licensed under AGPL or Commercial license.
 *
 * @package   maslosoft/mangan
 * @licence   AGPL or Commercial
 * @copyright Copyright (c) Piotr Masełkowski <pmaselkowski@gmail.com>
 * @copyright Copyright (c) Maslosoft
 * @copyright Copyright (c) Others as mentioned in code
 * @link      https://maslosoft.com/mangan/
 */

namespace Maslosoft\Mangan;

use Exception;
use Maslosoft\Addendum\Interfaces\AnnotatedInterface;
use Maslosoft\Mangan\Criteria\ConditionDecorator;
use Maslosoft\Mangan\Criteria\Conditions;
use Maslosoft\Mangan\Interfaces\Criteria\DecoratableInterface;
use Maslosoft\Mangan\Interfaces\Criteria\LimitableInterface;
use Maslosoft\Mangan\Interfaces\Criteria\MergeableInterface;
use Maslosoft\Mangan\Interfaces\Criteria\SelectableInterface;
use Maslosoft\Mangan\Interfaces\Criteria\SortableInterface;
use Maslosoft\Mangan\Interfaces\CriteriaInterface;
use Maslosoft\Mangan\Interfaces\ModelAwareInterface;
use Maslosoft\Mangan\Interfaces\SortInterface;
use Maslosoft\Mangan\Traits\Criteria\CursorAwareTrait;
use Maslosoft\Mangan\Traits\Criteria\DecoratableTrait;
use Maslosoft\Mangan\Traits\Criteria\LimitableTrait;
use Maslosoft\Mangan\Traits\Criteria\SelectableTrait;
use Maslosoft\Mangan\Traits\Criteria\SortableTrait;
use Maslosoft\Mangan\Traits\ModelAwareTrait;
use UnexpectedValueException;
use function is_array;

/**
 * Criteria
 *
 * This class is a helper for building MongoDB query arrays, it support three syntaxes for adding conditions:
 *
 * 1. 'equals' syntax:
 *    $criteriaObject->fieldName = $value; // this will produce fieldName == value query
 * 2. fieldName call syntax
 *    $criteriaObject->fieldName($operator, $value); // this will produce fieldName <operator> value
 *    $criteriaObject->fieldName($value); // this will produce fieldName == value
 * 3. addCond method
 *    $criteriaObject->addCond($fieldName, $operator, $vale); // this will produce fieldName <operator> value
 *
 * For operators list {@see Criteria::$operators}
 *
 * @author    Ianaré Sévi
 * @author    Dariusz Górecki <darek.krk@gmail.com>
 * @author    Invenzzia Group, open-source division of CleverIT company http://www.invenzzia.org
 * @copyright 2011 CleverIT http://www.cleverit.com.pl
 * @license   New BSD license
 */
class Criteria implements CriteriaInterface,
	ModelAwareInterface
{

	use CursorAwareTrait,
		DecoratableTrait,
		LimitableTrait,
		ModelAwareTrait,
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
	public const SortAsc = SortInterface::SortAsc;

	/**
	 * Sort Descending
	 */
	public const SortDesc = SortInterface::SortDesc;

	/**
	 * Sort Ascending
	 * @deprecated since version 4.0.7
	 */
	public const SORT_ASC = SortInterface::SortAsc;

	/**
	 * Sort Descending
	 * @deprecated since version 4.0.7
	 */
	public const SORT_DESC = SortInterface::SortDesc;

	private $_conditions = [];

	/**
	 * Raw conditions array
	 * @var mixed[]
	 */
	private $_rawConds = [];

	/**
	 * Currently used fields list. This is
	 * used to allow chained criteria creation.
	 *
	 * Example:
	 *
	 * ```
	 * $criteria->address->city->street->number = 666
	 * ```
	 *
	 * Will result in conditions:
	 *
	 * ```
	 * [
	 *    'address.city.street.number' = 666
	 * ]
	 * ```
	 *
	 * @var array
	 */
	private $_workingFields = [];

	/**
	 * Constructor
	 * Example criteria:
	 *
	 * <pre>
	 * $criteria = new Criteria(
	 * [
	 *    'conditions'=> [
	 *        'fieldName1' => ['greater' => 0],
	 *        'fieldName2' => ['>=' => 10],
	 *        'fieldName3' => ['<' => 10],
	 *        'fieldName4' => ['lessEq' => 10],
	 *        'fieldName5' => ['notEq' => 10],
	 *        'fieldName6' => ['in' => [10, 9]],
	 *        'fieldName7' => ['notIn' => [10, 9]],
	 *        'fieldName8' => ['all' => [10, 9]],
	 *        'fieldName9' => ['size' => 10],
	 *        'fieldName10' => ['exists'],
	 *        'fieldName11' => ['notExists'],
	 *        'fieldName12' => ['mod' => [10, 9]],
	 *        'fieldName13' => ['==' => 1]
	 *    ],
	 *    'select' => [
	 *        'fieldName',
	 *        'fieldName2'
	 *    ],
	 *    'limit' => 10,
	 *    'offset' => 20,
	 *    'sort'=>[
	 *        'fieldName1' => Criteria::SortAsc,
	 *        'fieldName2' => Criteria::SortDesc,
	 *    ]
	 * ]
	 * );
	 * </pre>
	 * @param mixed|CriteriaInterface|Conditions $criteria
	 * @param AnnotatedInterface|null $model Model to use for criteria decoration
	 * @throws Exception
	 */
	public function __construct($criteria = null, AnnotatedInterface $model = null)
	{
		if ($model !== null)
		{
			$this->setModel($model);
		}
		$this->setCd(new ConditionDecorator($model));

		if (!is_null($criteria) && !is_array($criteria) && !is_object($criteria))
		{
			$msg = sprintf('Criteria require array or another Criteria object however was provided: %s', $criteria);
			throw new UnexpectedValueException($msg);
		}

		if (is_array($criteria))
		{
			$available = ['conditions', 'select', 'limit', 'offset', 'sort', 'useCursor'];

			$diff = array_diff_key($criteria, array_flip($available));
			if (!empty($diff))
			{
				$params = [
					'[' . implode(', ', $available) . ']',
					'[' . implode(', ', array_keys($criteria)) . ']'
				];
				$msg = vsprintf('Allowed criteria keys are: %s, however was provided: %s', $params);
				throw new UnexpectedValueException($msg);
			}

			if (isset($criteria['conditions']))
			{
				foreach ($criteria['conditions'] as $fieldName => $conditions)
				{
					if(!is_array($conditions))
					{
						throw new UnexpectedValueException('Each condition must be array with operator as key and value, ie: ["_id" => ["==" => "123"]]');
					}
					foreach ($conditions as $operator => $value)
					{
						$operator = strtolower($operator);
						if (!isset(self::$operators[$operator]))
						{
							$params = [
								$operator,
								$fieldName
							];
							$msg = vsprintf('Unknown Criteria operator `%s` for `%s`', $params);
							throw new UnexpectedValueException($msg);
						}
						$this->addCond($fieldName, $operator, $value);
					}
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
		// Scrunitizer: $criteria is of type object<Maslosoft\Mangan\...ria\MergeableInterface>, but the function expects a array|object<Maslosoft\M...aces\CriteriaInterface>.
		// But for now it should be this way to easily distinguish from Conditions.
		// Future plan: Use CriteriaInterface here, and drop `$criteria instanceof Conditions` if clause. Conditions should implement CriteriaInterface too.
		elseif ($criteria instanceof MergeableInterface)
		{
			assert($criteria instanceof CriteriaInterface);
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
	 * - Limit and offset will be overridden
	 * - Select fields list will be merged
	 * - Sort fields list will be merged
	 * @param null|array|CriteriaInterface $criteria
	 * @return $this
	 * @throws Exception
	 */
	public function mergeWith($criteria)
	{
		if (is_array($criteria))
		{
			$criteria = new static($criteria, $this->getModel());
		}
		elseif (empty($criteria))
		{
			return $this;
		}

		// Set current criteria model if available
		$model = $this->getModel();

		// Fall back to merged criteria model
		if ($model === null)
		{
			$model = $criteria->getModel();
			if ($model !== null)
			{
				$this->setModel($model);
			}
		}

		// Use same model for decorating both criteria,
		// current one and merged one
		if ($model !== null)
		{
			if ($criteria instanceof DecoratableInterface)
			{
				$criteria->decorateWith($model);
			}

			if ($criteria instanceof ModelAwareInterface)
			{
				$criteria->setModel($model);
			}

			if ($this instanceof DecoratableInterface)
			{
				$this->decorateWith($model);
			}

			if ($this instanceof ModelAwareInterface)
			{
				$this->setModel($model);
			}
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

	/**
	 * Internal method for merging `_conditions` with `getConditions` call result.
	 * @param $source
	 * @param $conditions
	 * @return mixed Merged conditions array
	 */
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
	 * By-call-syntax criteria handler
	 *
	 * @param $fieldName
	 * @param mixed $parameters
	 * @return $this
	 */
	public function __call($fieldName, $parameters)
	{
		$operatorName = self::$operators['eq'];

		// Call with operator and value. Set
		// first param to be operator.
		if (array_key_exists(0, $parameters) && array_key_exists(1, $parameters))
		{
			$operatorName = strtolower($parameters[0]);
		}

		// Call without operator, use value only
		if (array_key_exists(0, $parameters) && !array_key_exists(1, $parameters))
		{
			$value = $parameters[0];
		}

		// Call with operator and value, use second param as value
		if (array_key_exists(1, $parameters))
		{
			$value = $parameters[1];
		}

		// ???
		if (is_numeric($operatorName))
		{
			$operatorName = strtolower(trim($value));
			$value = (strtolower(trim($value)) === 'exists') ? true : false;
		}

		if (!in_array($operatorName, array_keys(self::$operators)))
		{
			throw new UnexpectedValueException("Unknown operator: `$operatorName` on field `$fieldName`");
		}

		/**
		 * Support for syntax:
		 *
		 * ```
		 * $criteria->fieldOne->subField('op', 'value')
		 * ```
		 */

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

	/**
	 * This is required for chained criteria creating, ie
	 *
	 * ```
	 * $criteria->fieldOne->fieldTwo = 123;
	 * ```
	 *
	 * @param string $name
	 * @return $this
	 */
	public function __get($name)
	{
		array_push($this->_workingFields, $name);
		return $this;
	}

	/**
	 * By-set-syntax handler.
	 *
	 * This allows adding *equal* conditions by
	 * using field.
	 *
	 * Example:
	 *
	 * ```
	 * $criteria->userId = 1;
	 * ```
	 *
	 * @param string $name
	 * @param mixed $value
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
	 * @return array Query array
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
	 * @param mixed  $value
	 * @return $this
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
	 * Get condition
	 * If specified field already has a condition, values will be merged
	 * duplicates will be overridden by new values!
	 * @see   getConditions
	 * @param string $fieldName
	 * @param string $op operator
	 * @param mixed  $value
	 * @param array  $conditions
	 * @return array
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
