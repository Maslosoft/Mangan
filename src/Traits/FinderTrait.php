<?php

/**
 * This software package is licensed under New BSD license.
 *
 * @package maslosoft/mangan
 * @licence New BSD
 * @copyright Copyright (c) Piotr Masełkowski <pmaselkowski@gmail.com>
 * @copyright Copyright (c) Maslosoft
 * @copyright Copyright (c) Others as mentioned in code
 * @link http://maslosoft.com/mangan/
 */

namespace Maslosoft\Mangan\Traits;

use Maslosoft\Mangan\Criteria;
use Maslosoft\Mangan\Cursor;
use Maslosoft\Mangan\Document;
use Maslosoft\Mangan\Finder;
use Maslosoft\Mangan\Interfaces\IFinder;
use Maslosoft\Mangan\Interfaces\IModel;

/**
 * FinderTrait
 * @see IFinder
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
	 * @param array|Criteria $criteria query criteria.
	 *
	 * If an array, it is treated as the initial values for constructing a {@link Criteria} object;
	 * Otherwise, it should be an instance of {@link Criteria}.
	 *
	 * @return Document the record found. Null if no record is found.
	 * @since v1.0
	 */
	public function find($criteria = null)
	{
		$this->_getFinder()->find($criteria);
	}

	/**
	 * Finds all documents satisfying the specified condition.
	 * See {@link find()} for detailed explanation about $condition and $params.
	 * @param array|Criteria $criteria query criteria.
	 * @return IModel[]|Cursor array list of documents satisfying the specified condition. An empty array is returned if none is found.
	 * @since v1.0
	 */
	public function findAll($criteria = null)
	{
		return $this->_getFinder()->findAll($criteria);
	}

	/**
	 * Finds all documents with the specified attributes.
	 *
	 * @param mixed[] Array of stributes and values in form of ['attributeName' => 'value']
	 * @return IModel[]|Cursor - Array or cursor of Documents
	 * @since v1.0
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
	 * @param mixed $pk primary key value(s). Use array for multiple primary keys. For composite key, each key value must be an array (column name=>column value).
	 * @param array|Criteria $criteria query criteria.
	 * @return IModel[]|Cursor - Array or cursor of Documents
	 * @since v1.0
	 */
	public function findAllByPk($pk, $criteria = null)
	{
		return $this->_getFinder()->findAllByPk($pk, $criteria);
	}

	/**
	 * Finds document with the specified primary key.
	 * In MongoDB world every document has '_id' unique field, so with this method that
	 * field is in use as PK!
	 * See {@link find()} for detailed explanation about $condition.
	 * @param mixed $pk primary key value(s). Use array for multiple primary keys. For composite key, each key value must be an array (column name=>column value).
	 * @param array|Criteria $criteria query criteria.
	 * @return Document the document found. An null is returned if none is found.
	 * @since v1.0
	 */
	public function findByPk($pk, $criteria = null)
	{
		return $this->_getFinder()->findByPk($pk, $criteria);
	}

	/**
	 * Finds document with the specified attributes.
	 *
	 * See {@link find()} for detailed explanation about $condition.
	 * @param mixed[] Array of stributes and values in form of ['attributeName' => 'value']
	 * @return Document - the document found. An null is returned if none is found.
	 * @since v1.0
	 */
	public function findByAttributes(array $attributes)
	{
		return $this->_getFinder()->findByAttributes($attributes);
	}

	/**
	 * Counts all documents satisfying the specified condition.
	 * See {@link find()} for detailed explanation about $condition and $params.
	 * @param array|Criteria $criteria query criteria.
	 * @return integer Count of all documents satisfying the specified condition.
	 * @since v1.0
	 */
	public function count($criteria = null)
	{
		return $this->_getFinder()->count($criteria);
	}

	/**
	 * Counts all documents satisfying the specified condition.
	 * See {@link find()} for detailed explanation about $condition and $params.
	 * @param mixed[] Array of stributes and values in form of ['attributeName' => 'value']
	 * @return integer Count of all documents satisfying the specified condition.
	 * @since v1.2.2
	 */
	public function countByAttributes(array $attributes)
	{
		return $this->_getFinder()->countByAttributes($attributes);
	}

	/**
	 * Checks whether there is row satisfying the specified condition.
	 * See {@link find()} for detailed explanation about $condition and $params.
	 * @param mixed $criteria query condition or criteria.
	 * @param array $params parameters to be bound to an SQL statement.
	 * @return boolean whether there is row satisfying the specified condition.
	 */
	public function exists(Criteria $criteria = null)
	{
		return $this->_getFinder()->exists($criteria);
	}

	/**
	 * Whenever to use cursor
	 * @param type $useCursor
	 * @return IFinder
	 */
	public function withCursor($useCursor = true)
	{
		return $this->_getFinder()->withCursor($useCursor);
	}

	private function _getFinder()
	{
		if (null === $this->_finder)
		{
			$this->_finder = Finder::create($this);
		}
		return $this->_finder;
	}

}
