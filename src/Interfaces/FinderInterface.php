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

namespace Maslosoft\Mangan\Interfaces;

use Maslosoft\Addendum\Interfaces\AnnotatedInterface;
use Maslosoft\Mangan\Criteria;
use Maslosoft\Mangan\Cursor;

/**
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
interface FinderInterface
{

	/**
	 * This event is triggered before an AR finder executes a find call.
	 * The find calls include {@link find}, {@link findAll}, {@link findByPk},
	 * {@link findAllByPk}, {@link findByAttributes} and {@link findAllByAttributes}.
	 */
	const EventBeforeFind = 'beforeFind';

	/**
	 * This event is triggered before count methods
	 */
	const EventBeforeCount = 'beforeCount';

	/**
	 * This event is triggered before exists methods
	 */
	const EventBeforeExists = 'beforeExists';

	/**
	 * This event is trigerred after each record is instantiated by a find method.
	 * The find calls include {@link find}, {@link findAll}, {@link findByPk},
	 * {@link findAllByPk}, {@link findByAttributes} and {@link findAllByAttributes}.
	 */
	const EventAfterFind = 'afterFind';

	/**
	 * This event is triggered after count methods
	 */
	const EventAfterCount = 'afterCount';

	/**
	 * This event is triggered after exists methods
	 */
	const EventAfterExists = 'afterExists';

	/**
	 * Finds a single Document with the specified condition.
	 *
	 * @param array|CriteriaInterface $criteria query criteria.
	 * @return AnnotatedInterface
	 * @Ignored
	 */
	public function find($criteria = null);

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
	 * @param array|CriteriaInterface|Criteria $criteria
	 * @return AnnotatedInterface|null
	 * @Ignored
	 */
	public function findByPk($pk, $criteria = null);

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
	 */
	public function findByAttributes(array $attributes);

	/**
	 * Finds all documents satisfying the specified condition.
	 *
	 * @param array|CriteriaInterface $criteria query criteria.
	 * @return AnnotatedInterface[]|Cursor
	 * @Ignored
	 */
	public function findAll($criteria = null);

	/**
	 * Finds all documents with the specified attributes.
	 *
	 * @param mixed[] Array of stributes and values in form of ['attributeName' => 'value']
	 * @return AnnotatedInterface[]|Cursor - Array or cursor of Documents
	 * @since v1.0
	 */
	public function findAllByAttributes(array $attributes);

	/**
	 * Finds all documents with the specified primary keys.
	 * In MongoDB world every document has '_id' unique field, so with this method that
	 * field is in use as PK by default.
	 * See {@link find()} for detailed explanation about $condition.
	 * @param mixed $pk primary key value(s). Use array for multiple primary keys. For composite key, each key value must be an array (column name=>column value).
	 * @param array|CriteriaInterface $criteria query criteria.
	 * @return AnnotatedInterface[]|Cursor - Array or cursor of Documents
	 * @since v1.0
	 */
	public function findAllByPk($pk, $criteria = null);

	/**
	 * Counts all documents satisfying the specified condition.
	 * See {@link find()} for detailed explanation about $condition and $params.
	 * @param array|CriteriaInterface $criteria query criteria.
	 * @return integer Count of all documents satisfying the specified condition.
	 * @since v1.0
	 */
	public function count($criteria = null);

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
	 * @since v1.2.2
	 * @Ignored
	 */
	public function countByAttributes(array $attributes);

	/**
	 * Checks whether there is document satisfying the specified condition.
	 *
	 * @param CriteriaInterface|null $criteria
	 * @return bool
	 */
	public function exists(?CriteriaInterface $criteria = null);

	/**
	 * Whenever to use cursor
	 *
	 * @param type $useCursor
	 * @return FinderInterface
	 */
	public function withCursor($useCursor = true);
}
