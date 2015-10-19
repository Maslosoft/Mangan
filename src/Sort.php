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

use InvalidArgumentException;
use Maslosoft\Addendum\Interfaces\AnnotatedInterface;
use Maslosoft\Mangan\Criteria\ConditionDecorator;
use Maslosoft\Mangan\Interfaces\SortInterface;

/**
 * Sort
 */
class Sort implements SortInterface
{

	/**
	 * Sort Ascending
	 * Alias left for BC
	 * @deprecated since version 4.0.7
	 */
	const SORT_ASC = self::SortAsc;

	/**
	 * Sort Descending
	 * Alias left for BC
	 * @deprecated since version 4.0.7
	 */
	const SORT_DESC = self::SortDesc;

	/**
	 * @Ignore
	 * @var AnnotatedInterface
	 */
	public $model = null;

	/**
	 *
	 * @var int[]
	 */
	public $fields = [];

	/**
	 * Condition decorator instance
	 * @var ConditionDecorator
	 */
	private $cd;

	public function __construct($sort = [], AnnotatedInterface $model = null)
	{
		foreach ($sort as $field => $order)
		{
			$this->replace($field, $order);
		}
		$this->setModel($model);
	}

	/**
	 * Add sorting field order. If field is already declared it will be pushed to the end of sort list.
	 * @param string $field
	 * @param int $order
	 */
	public function add($field, $order)
	{
		$this->remove($field);
		$this->replace($field, $order);
		return $this;
	}

	/**
	 * Create or replace sorting field order. If field is already declared it will **not** be pushed to the end of sort list.
	 * @param string $field
	 * @param int $order
	 */
	public function replace($field, $order)
	{
		if (!in_array($order, [self::SortAsc, self::SortDesc]))
		{
			throw new InvalidArgumentException(sprintf('Invalid order `%s` value for field `%s` of model %s', $order, $field, get_class($this->model)));
		}
		$this->fields[$field] = $order;
		return $this;
	}

	/**
	 * Remove sort field
	 * @param string $field
	 */
	public function remove($field)
	{
		if (isset($this->fields[$field]))
		{
			unset($this->fields[$field]);
		}
		return $this;
	}

	/**
	 * Returns true if sorting is applied
	 * @return bool
	 */
	public function isSorted()
	{
		return count($this->fields) > 0;
	}

	public function getSort()
	{
		$sort = [];
		if (null === $this->cd)
		{
			$this->cd = new ConditionDecorator($this->model);
		}
		foreach ($this->fields as $fieldName => $order)
		{
			$decorated = $this->cd->decorate($fieldName);
			$sort[key($decorated)] = $order;
		}
		return $sort;
	}

	public function setModel(AnnotatedInterface $model = null)
	{
		if (null !== $model)
		{
			$this->model = $model;
		}
		return $this;
	}

}
