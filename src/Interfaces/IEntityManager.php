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

namespace Maslosoft\Mangan\Interfaces;

use Maslosoft\Addendum\Interfaces\AnnotatedInterface;
use Maslosoft\Mangan\Criteria;
use Maslosoft\Mangan\Modifier;
use MongoCollection;
use MongoException;

/**
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
interface IEntityManager
{

	const EventAfterSave = 'afterSave';
	const EventBeforeSave = 'beforeSave';
	const EventBeforeDelete = 'beforeDelete';
	const EventAfterDelete = 'afterDelete';

	/**
	 * Inserts a row into the table based on this active record attributes.
	 * If the table's primary key is auto-incremental and is null before insertion,
	 * it will be populated with the actual value after insertion.
	 * Note, validation is not performed in this method. You may call {@link validate} to perform the validation.
	 * After the record is inserted to DB successfully, its {@link isNewRecord} property will be set false,
	 * and its {@link scenario} property will be set to be 'update'.
	 * @param AnnotatedInterface $model if want to insert different model than set in constructor
	 * @return boolean whether the attributes are valid and the record is inserted successfully.
	 * @throws MongoException if the record is not new
	 * @throws MongoException on fail of insert or insert of empty document
	 * @throws MongoException on fail of insert, when safe flag is set to true
	 * @throws MongoException on timeout of db operation , when safe flag is set to true
	 * @since v1.0
	 */
	public function insert(AnnotatedInterface $model = null);

	/**
	 * Updates the row represented by this active record.
	 * All loaded attributes will be saved to the database.
	 * Note, validation is not performed in this method. You may call {@link validate} to perform the validation.
	 * @param array $attributes list of attributes that need to be updated. Defaults to null,
	 * meaning all attributes that are loaded from DB will be saved.
	 * @return boolean whether the update is successful
	 * @throws MongoException if the record is new
	 * @throws MongoException on fail of update
	 * @throws MongoException on timeout of db operation , when safe flag is set to true
	 * @since v1.0
	 */
	public function update(array $attributes = null);

	/**
	 * Atomic, in-place update method.
	 *
	 * @since v1.3.6
	 * @param Modifier $modifier updating rules to apply
	 * @param Criteria $criteria condition to limit updating rules
	 * @return boolean|mixed[]
	 */
	public function updateAll(Modifier $modifier, Criteria $criteria = null);

	/**
	 * Saves the current record.
	 *
	 * The record is inserted as a row into the database table if its {@link isNewRecord}
	 * property is true (usually the case when the record is created using the 'new'
	 * operator). Otherwise, it will be used to update the corresponding row in the table
	 * (usually the case if the record is obtained using one of those 'find' methods.)
	 *
	 * Validation will be performed before saving the record. If the validation fails,
	 * the record will not be saved. You can call {@link getErrors()} to retrieve the
	 * validation errors.
	 *
	 * If the record is saved via insertion, its {@link isNewRecord} property will be
	 * set false, and its {@link scenario} property will be set to be 'update'.
	 * And if its primary key is auto-incremental and is not set before insertion,
	 * the primary key will be populated with the automatically generated key value.
	 *
	 * @param boolean $runValidation whether to perform validation before saving the record.
	 * If the validation fails, the record will not be saved to database.
	 * @param array $attributes list of attributes that need to be saved. Defaults to null,
	 * meaning all attributes that are loaded from DB will be saved.
	 * @return boolean whether the saving succeeds
	 * @since v1.0
	 */
	public function save($runValidation = true, $attributes = null);

	/**
	 * Deletes the row corresponding to this Document.
	 * @return boolean whether the deletion is successful.
	 * @throws MongoException if the record is new
	 * @since v1.0
	 */
	public function delete();

	/**
	 * Deletes one document with the specified primary keys.
	 * <b>Does not raise beforeDelete</b>
	 * See {@link find()} for detailed explanation about $condition and $params.
	 * @param array|Criteria $criteria query criteria.
	 * @since v1.0
	 */
	public function deleteOne($criteria = null);

	/**
	 * Deletes document with the specified primary key.
	 * See {@link find()} for detailed explanation about $condition and $params.
	 * @param mixed $pkValue primary key value(s). Use array for multiple primary keys. For composite key, each key value must be an array (column name=>column value).
	 * @param array|Criteria $criteria query criteria.
	 * @since v1.0
	 */
	public function deleteByPk($pkValue, $criteria = null);

	/**
	 * Deletes documents with the specified primary keys.
	 * See {@link find()} for detailed explanation about $condition and $params.
	 * @param mixed[] $pkValues Primary keys array
	 * @param array|Criteria $criteria query criteria.
	 * @since v1.0
	 */
	public function deleteAllByPk($pkValues, $criteria = null);

	/**
	 * Deletes documents with the specified primary keys.
	 * See {@link find()} for detailed explanation about $condition and $params.
	 * @param array|Criteria $criteria query criteria.
	 * @since v1.0
	 */
	public function deleteAll($criteria = null);

	/**
	 * Repopulates this active record with the latest data.
	 * @return boolean whether the row still exists in the database. If true, the latest data will be populated to this active record.
	 * @since v1.0
	 */
	public function refresh();

	/**
	 * Get mongodb collection
	 * @return MongoCollection PHP Driver MongoCollection instance
	 */
	public function getCollection();
}
