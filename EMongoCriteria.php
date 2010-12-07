<?php

class EMongoCriteria extends CComponent
{
	public static $operators = array(
		'greater'		=> '$gt',
		'>'				=> '$gt',
		'greatereq'		=> '$gte',
		'>='			=> '$gte',
		'less'			=> '$lt',
		'<'				=> '$lt',
		'lesseq'		=> '$lte',
		'<='			=> '$lte',
		'noteq'			=> '$ne',
		'!='			=> '$ne',
		'<>'			=> '$ne',
		'in'			=> '$in',
		'notin'			=> '$nin',
		'all'			=> '$all',
		'size'			=> '$size',
		'type'			=> '$type',
		'exists'		=> '$exists',
		'notexists'		=> '$exists',
		'elemmatch'		=> '$elemMatch',
		'mod'			=> '$mod',
		'%'				=> '$mod',
		'equals'		=> '$$eq',
		'=='			=> '$$eq',
		'where'			=> '$where'
	);

	const SORT_ASC		= 1;
	const SORT_DESC		= -1;

	private $_select		= array();
	private $_limit			= null;
	private $_offset		= null;
	private $_conditions	= array();
	private $_sort			= array();
	private $_workingFields	= array();

	/**
	 * Constructor
	 * Example criteria:
	 *
	 * <PRE>
	 * 'criteria' = array(
	 * 	'conditions'=>array(
	 *		'fieldName1'=>array('greater', 0),
	 *		'fieldName2'=>array('greaterEq', 10),
	 *		'fieldName3'=>array('less', 10),
	 *		'fieldName4'=>array('lessEq', 10),
	 *		'fieldName5'=>array('notEq', 10),
	 *		'fieldName6'=>array('in', array(10, 9)),
	 *		'fieldName7'=>array('notIn', array(10, 9)),
	 *		'fieldName8'=>array('all', array(10, 9)),
	 *		'fieldName9'=>array('size', 10),
	 *		'fieldName10'=>array('exists'),
	 *		'fieldName11'=>array('notExists'),
	 *		'fieldName12'=>array('mod', array(10, 9)),
	 * 		'fieldName13'=>array('equals', 1)
	 * 	),
	 * 	'select'=>array('fieldName', 'fieldName2'),
	 * 	'limit'=>10,
	 *  'offset'=>20,
	 *  'sort'=>array('fieldName1'=>EMongoCriteria::SORT_ASC, 'fieldName2'=>EMongoCriteria::SORT_DESC),
	 * );
	 * </PRE>
	 * @param unknown_type $criteria
	 */
	public function __construct($criteria=null)
	{
		if(is_array($criteria))
		{
			if(isset($criteria['conditions']))
				foreach($criteria['conditions'] as $fieldName=>$cond)
				{
					$operator = strtolower(array_shift($cond));
					$value = array_shift($cond);
					call_user_func_array(array($this, $fieldName), array($operator, $value));
				}
			if(isset($criteria['select']))
				$this->select($criteria['select']);
			if(isset($criteria['limit']))
				$this->limit($criteria['limit']);
			if(isset($criteria['offset']))
				$this->offset($criteria['offset']);
			if(isset($criteria['sort']))
				$this->setSort($criteria['sort']);
		}
	}

	/**
	 * Merge with other criteria
	 * Existing fields operators, limit and offet will be overriden
	 * select fields will be merged
	 * @param array|EMongoCriteria $criteria
	 */
	public function mergeWith($criteria)
	{
		if(is_array($criteria))
			$criteria = new EMongoCriteria($criteria);
		else if(empty($criteria))
		{
			return $this;
		}

		foreach($criteria->_conditions as $fieldName=>$conds)
		{
			if(
				is_array($conds) &&
				count(array_diff(array_keys($conds), array_values(self::$operators))) == 0
			)
			{
				if(!isset($this->_conditions[$fieldName]))
					$this->_conditions[$fieldName] = array();
				foreach($conds as $operator=>$value)
					$this->_conditions[$fieldName][$operator] = $value;
			}
			else
				$this->_conditions[$fieldName] = $conds;
		}

		if(!empty($criteria->_limit))
			$this->_limit	= $criteria->_limit;
		if(!empty($criteria->_offset))
			$this->_offset	= $criteria->_offset;
		if(!empty($criteria->_sort))
			$this->_sort	= array_merge($this->_sort, $criteria->_sort);

		return $this;
	}

	/**
	 * If we have operator add it otherwise call parent implementation
	 * @see CComponent::__call()
	 */
	public function __call($fieldName, $parameters)
	{
		$operatorName = strtolower(array_shift($parameters));
		$value = array_shift($parameters);
		if(in_array($operatorName, array_keys(self::$operators)))
		{
			array_push($this->_workingFields, $fieldName);
			$fieldName = implode('.', $this->_workingFields);
			$this->_workingFields = array();
			switch($operatorName)
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
		else
			return parent::__call($name, $parameters);
	}

	public function __get($name)
	{
		array_push($this->_workingFields, $name);
		return $this;
	}

	public function __set($name, $value)
	{
		array_push($this->_workingFields, $name);
		$fieldList = implode('.', $this->_workingFields);
		$this->_workingFields = array();
		$this->addCond($fieldList, '==', $value);
	}

	/**
	 * Return query array
	 * @return array query array
	 */
	public function getConditions()
	{
		return $this->_conditions;
	}

	public function setConditions(array $conditions)
	{
		$this->_conditions = $conditions;
	}

	public function getLimit()
	{
		return $this->_limit;
	}

	public function setLimit($limit)
	{
		$this->limit($limit);
	}

	public function getOffset()
	{
		return $this->_offset;
	}

	public function setOffset($offset)
	{
		$this->offset($offset);
	}

	public function getSort()
	{
		return $this->_sort;
	}

	public function setSort(array $sort)
	{
		$this->_sort = $sort;
	}

	/**
	 * List of fields to get from DB
	 * Multiple calls to this method will merge all given fields
	 *
	 * @param array $fieldList list of fields to select
	 */
	public function select(array $fieldList=null)
	{
		if($fieldList!==null)
			$this->_select = array_merge($this->_select, $fieldList);
		return $this;
	}

	/**
	 * Set linit
	 * Multiple calls will overrride previous value of limit
	 *
	 * @param integer $limit limit
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
	 */
	public function offset($offset)
	{
		$this->_offset = intval($offset);
		return $this;
	}

	/**
	 * Add sorting, avaliabe orders are: EMongoCriteria::SORT_ASC and EMongoCriteria::SORT_DESC
	 * Each call will be groupped with previous calls
	 * @param string $fieldName
	 * @param integer $order
	 */
	public function sort($fieldName, $order)
	{
		$this->_sort[$fieldName] = intval($order);
		return $this;
	}

	/**
	 * Add condition
	 * If specified field already has a condition, values will be merged
	 * duplicates will be overriden by new values!
	 * @param string $fieldName
	 * @param string $op operator
	 * @param mixed $value
	 */
	public function addCond($fieldName, $op, $value)
	{
		$op = self::$operators[$op];
		if(!isset($this->_conditions[$fieldName]) && $op != self::$operators['equals'])
			$this->_conditions[$fieldName] = array();

		if($op != self::$operators['equals'])
		{
			if(
				!is_array($this->_conditions[$fieldName]) ||
				count(array_diff(array_keys($this->_conditions[$fieldName]), array_values(self::$operators))) > 0
			)
			{
				$this->_conditions[$fieldName] = array();
			}
			$this->_conditions[$fieldName][$op] = $value;
		}
		else
			$this->_conditions[$fieldName] = $value;

		return $this;
	}
}