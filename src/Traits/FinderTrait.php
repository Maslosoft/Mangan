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

namespace Maslosoft\Mangan\Traits;

use Maslosoft\Addendum\Interfaces\AnnotatedInterface;
use Maslosoft\Mangan\Cursor;
use Maslosoft\Mangan\Document;
use Maslosoft\Mangan\Finder;
use Maslosoft\Mangan\Interfaces\CriteriaInterface;
use Maslosoft\Mangan\Interfaces\FinderInterface;

/**
 * FinderTrait
 * @see FinderInterface
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
trait FinderTrait
{

	/**
	 * Finder
	 * @var Finder
	 */
	private $_finder = null;

	/**
	 * Finds a single Document with the specified condition.
	 *
	 * @param array|CriteriaInterface $criteria query criteria.
	 * @return AnnotatedInterface|null
	 * @Ignored
	 */
	public function find($criteria = null)
	{
		return $this->_getFinder()->find($criteria);
	}

	/**
	 * Finds document with the specified primary key. Primary key by default
	 * is defined by `_id` field. But could be any other. For simple (one column)
	 * keys use it's value.
	 *
	 * For composite use key-value with column names as keys
	 * and values for values.
	 *
	 * Example for simple pk:
	 * ```php
	 * $pk = '51b616fcc0986e30026d0748'
	 * ```
	 *
	 * Composite pk:
	 * ```php
	 * $pk = [
	 * 		'mainPk' => 1,
	 * 		'secondaryPk' => 2
	 * ];
	 * ```
	 *
	 * @param mixed $pk primary key value. Use array for composite key.
	 * @param array|CriteriaInterface $criteria
	 * @return AnnotatedInterface|null
	 * @Ignored
	 */
	public function findByPk($pk, $criteria = null)
	{
		return $this->_getFinder()->findByPk($pk, $criteria);
	}

	/**
	 * Finds document with the specified attributes.
	 * Attributes should be specified as key-value pairs.
	 * This allows easier syntax for simple queries.
	 *
	 * Example:
	 * ```php
	 * $attributes = [
	 * 		'name' => 'John',
	 * 		'title' => 'dr'
	 * ];
	 * ```
	 *
	 * @param mixed[] Array of stributes and values in form of ['attributeName' => 'value']
	 * @return AnnotatedInterface|null
	 * @Ignored
	 */
	public function findByAttributes(array $attributes)
	{
		return $this->_getFinder()->findByAttributes($attributes);
	}

	/**
	 * Finds all documents satisfying the specified condition.
	 *
	 * @param array|CriteriaInterface $criteria query criteria.
	 * @return AnnotatedInterface[]|Cursor
	 * @Ignored
	 */
	public function findAll($criteria = null)
	{
		return $this->_getFinder()->findAll($criteria);
	}

	/**
	 * Finds all documents with the specified attributes.
	 *
	 * @param mixed[] Array of stributes and values in form of ['attributeName' => 'value']
	 * @return AnnotatedInterface[]|Cursor
	 * @Ignored
	 */
	public function findAllByAttributes(array $attributes)
	{
		return $this->_getFinder()->findAllByAttributes($attributes);
	}

	/**
	 * Finds all documents with the specified primary keys.
	 * In MongoDB world every document has '_id' unique field, so with this method that
	 * field is in use as PK by default.
	 * See {@link find()} for detailed explanation about $condition.
	 *
	 * @param mixed $pk primary key value(s). Use array for multiple primary keys. For composite key, each key value must be an array (column name=>column value).
	 * @param array|CriteriaInterface $criteria query criteria.
	 * @return AnnotatedInterface[]|Cursor
	 * @Ignored
	 */
	public function findAllByPk($pk, $criteria = null)
	{
		return $this->_getFinder()->findAllByPk($pk, $criteria);
	}

	/**
	 * Counts all documents satisfying the specified condition.
	 *
	 * @param array|CriteriaInterface $criteria query criteria.
	 * @return int
	 * @Ignored
	 */
	public function count($criteria = null)
	{
		return $this->_getFinder()->count($criteria);
	}

	/**
	 * Counts all documents found by attribute values.
	 *
	 * Example:
	 * ```php
	 * $attributes = [
	 * 		'name' => 'John',
	 * 		'title' => 'dr'
	 * ];
	 * ```
	 *
	 * @param mixed[] Array of attributes and values in form of ['attributeName' => 'value']
	 * @return int
	 * @Ignored
	 */
	public function countByAttributes(array $attributes)
	{
		return $this->_getFinder()->countByAttributes($attributes);
	}

	/**
	 * Checks whether there is document satisfying the specified condition.
	 *
	 * @param CriteriaInterface $criteria
	 * @return bool
	 * @Ignored
	 */
	public function exists(CriteriaInterface $criteria = null)
	{
		return $this->_getFinder()->exists($criteria);
	}

	/**
	 * Whenever to use cursor
	 *
	 * @param type $useCursor
	 * @return FinderInterface
	 * @Ignored
	 */
	public function withCursor($useCursor = true)
	{
		return $this->_getFinder()->withCursor($useCursor);
	}

	/**
	 * Get finder instace
	 * @return FinderInterface
	 */
	private function _getFinder()
	{
		if (null === $this->_finder)
		{
			$this->_finder = Finder::create($this);
		}
		return $this->_finder;
	}

}
