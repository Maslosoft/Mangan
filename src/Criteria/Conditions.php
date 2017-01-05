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

namespace Maslosoft\Mangan\Criteria;

use Maslosoft\Addendum\Interfaces\AnnotatedInterface;
use Maslosoft\Mangan\Criteria;

/**
 * Conditions builder helper class. This exposes fluid interface for building complex queries.
 *
 * Building complex queries can lead to (semi) manually constructing deeply nested array of conditions,
 * which is both unreadable and unreliable, espacially when user with `$or`, `$and`, `$not` etc.
 *
 * This class is meant to overcome this issue, by providing fluid, cascading interface.
 *
 * Example for simple query, let's find `visits` larger than 30 and lesser than 100:
 * 
 * ```php
 * $conditions = new Conditions($model)
 * $conditions->visits->gt(30)->lt(100);
 * ```
 *
 * However real improvement comes when used with more complex query. In this example we
 * search for `visits` greater than 10 and lesser than 200 or greater than 100 and
 * lesser than 200, where `active` is true:
 *
 * ```php
 * $c1 = new Conditions($model);
 * $c1->visits->gt(10)->lt(20);
 *
 * $c2->visits->gt(100)->lt(200);
 *
 * $condtions = new Conditions($model);
 * $conditions->active = true;
 *
 * $conditions->or($c1, $c2);
 * ```
 *
 * In above example mongodb conditions array is quite verbose and depth, however
 * with `Conditions` class it is fairly clean and easy to create it.
 *
 * When conditions setup is finished, this should be passed to `Criteria` by `setCriteria`:
 *
 * ```php
 * $criteria = new Criteria();
 * $criteria->setconditions($conditions);
 * ```
 *
 * Or alternatively it can be passed directly into constructor:
 * ```
 * $criteria = new Criteria($conditions);
 * ```
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class Conditions
{

	const FieldName = 1;
	const Operator = 2;
	const Value = 3;

	private $model = null;
	private $criteria = null;
	private $field = '';

	public function __construct(AnnotatedInterface $model)
	{
		$this->criteria = new Criteria(null, $model);
	}

	public function __get($name)
	{
		$this->field = $name;
		return $this;
	}

	public function __set($name, $value)
	{
		$this->field = $name;
		$this->eq($value);
		return $this;
	}

	public function eq($value)
	{
		$this->criteria->addCond($this->field, 'eq', $value);
		return $this;
	}

	public function gt($value)
	{
		$this->criteria->addCond($this->field, 'gt', $value);
		return $this;
	}

	public function gte($value)
	{
		$this->criteria->addCond($this->field, 'gte', $value);
		return $this;
	}

	public function lt($value)
	{
		$this->criteria->addCond($this->field, 'lt', $value);
		return $this;
	}

	public function lte($value)
	{
		$this->criteria->addCond($this->field, 'lte', $value);
		return $this;
	}

	public function ne($value)
	{
		$this->criteria->addCond($this->field, 'ne', $value);
		return $this;
	}

	public function in(array $values)
	{
		return $this;
	}

	public function notIn(array $values)
	{
		return $this;
	}

	/**
	 *
	 * @param Conditions|Conditions[] $conditions
	 * @return Conditions
	 */
	public function addOr($conditions)
	{
		return $this;
	}

	public function addAnd($value)
	{
		return $this;
	}

	public function addNot($value)
	{
		return $this;
	}

	public function addNor($value)
	{
		return $this;
	}

	public function get()
	{
		return $this->criteria->getConditions();
	}

}
