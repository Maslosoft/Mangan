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
use Maslosoft\Mangan\Exceptions\ManganException;
use Maslosoft\Mangan\Modifier;
use MongoCollection;

/**
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
interface EntityManagerInterface
{
	const AspectSaving = 'saving';
	const AspectRemoving = 'removing';
	const EventBeforeSave = 'beforeSave';
	const EventAfterSave = 'afterSave';
	const EventBeforeInsert = 'beforeInsert';
	const EventAfterInsert = 'afterInsert';
	const EventBeforeUpdate = 'beforeUpdate';
	const EventAfterUpdate = 'afterUpdate';
	const EventBeforeDelete = 'beforeDelete';
	const EventAfterDelete = 'afterDelete';

	/**
	 * Replaces the current document.
	 *
	 * **NOTE: This will overwrite entire document.**
	 * Any filtered out properties will be removed as well.
	 *
	 * The record is inserted as a documnent into the database collection, if exists it will be replaced.
	 *
	 * Validation will be performed before saving the record. If the validation fails,
	 * the record will not be saved. You can call {@link getErrors()} to retrieve the
	 * validation errors.
	 *
	 * @param boolean $runValidation whether to perform validation before saving the record.
	 * If the validation fails, the record will not be saved to database.
	 *
	 * @return boolean whether the saving succeeds
	 * @since v1.0
	 */
	public function replace($runValidation = true);

	/**
	 * Inserts a row into the table based on this active record attributes.
	 * If the table's primary key is auto-incremental and is null before insertion,
	 * it will be populated with the actual value after insertion.
	 * Note, validation is not performed in this method. You may call {@link validate} to perform the validation.
	 * After the record is inserted to DB successfully, its {@link isNewRecord} property will be set false,
	 * and its {@link scenario} property will be set to be 'update'.
	 * 
	 * @param AnnotatedInterface $model if want to insert different model than set in constructor
	 * @return boolean whether the attributes are valid and the record is inserted successfully.
	 * @throws ManganException if the record is not new
	 * @throws ManganException on fail of insert or insert of empty document
	 * @throws ManganException on fail of insert, when safe flag is set to true
	 * @throws ManganException on timeout of db operation , when safe flag is set to true
	 * @since v1.0
	 */
	public function insert(AnnotatedInterface $model = null);

	/**
	 * Updates the document represented by this active record.
	 * All loaded attributes will be saved to the database.
	 *
	 * Note, validation is not performed in this method. You may call {@link validate} to perform the validation.
	 *
	 * @param array $attributes list of attributes that need to be updated. Defaults to null,
	 * meaning all attributes that are loaded from DB will be saved.
	 * @return boolean whether the update is successful
	 * @throws ManganException if the record is new
	 * @throws ManganException on fail of update
	 * @throws ManganException on timeout of db operation , when safe flag is set to true
	 * @since v1.0
	 */
	public function update(array $attributes = null);

	/**
	 * Updates one document with the specified criteria and attributes
	 *
	 * This is more *raw* update:
	 *
	 * * Does not raise any events or signals
	 * * Does not perform any validation
	 *
	 * @param array|CriteriaInterface $criteria query criteria.
	 * @param array $attributes list of attributes that need to be saved. Defaults to null,
	 * meaning all attributes that are loaded from DB will be saved.
	 * @param bool Whether tu force update/upsert document
	 * @since v1.0
	 */
	public function updateOne($criteria = null, array $attributes = null, $modify = false);

	/**
	 * Atomic, in-place update method.
	 *
	 * @since v1.3.6
	 * @param Modifier $modifier updating rules to apply
	 * @param CriteriaInterface $criteria condition to limit updating rules
	 * @return boolean|mixed[]
	 */
	public function updateAll(Modifier $modifier, CriteriaInterface $criteria = null);

	/**
	 * Saves the current record. Will insert new document, or update if exists.
	 *
	 * Validation will be performed before saving the document. If the validation fails,
	 * the record will not be saved. You can call {@link getErrors()} to retrieve the
	 * validation errors.
	 *
	 * @param boolean $runValidation whether to perform validation before saving the record.
	 * If the validation fails, the record will not be saved to database.

	 * @return boolean whether the saving succeeds
	 * @since v1.0
	 */
	public function save($runValidation = true);

	/**
	 * Updates or inserts the current document. This will try to update existing fields.
	 * Will keep already stored data if present in document.
	 *
	 * If document does not exist, a new one will be inserted.
	 *
	 * @param boolean $runValidation
	 * @return boolean
	 * @throws ManganException
	 */
	public function upsert($runValidation = true);

	/**
	 * Deletes the row corresponding to this Document.
	 * 
	 * @return boolean whether the deletion is successful.
	 * @throws ManganException if the record is new
	 * @since v1.0
	 */
	public function delete();

	/**
	 * Deletes one document with the specified primary key or by passed criteria.
	 * 
	 * This is more *raw* method than delete:
	 *
	 * * Does not raise events
	 * * Does not emit signals
	 *
	 * See `Criteria` class for detailed explanation about $criteria param.
	 * @param array|CriteriaInterface $criteria query criteria.
	 * @see Criteria
	 * @see CriteriaInterface
	 * @since v1.0
	 */
	public function deleteOne($criteria = null);

	/**
	 * Deletes document with the specified primary key with optional criteria.
	 *
	 * See `Criteria` class for detailed explanation about $criteria param.
	 *
	 * @param mixed $pkValue primary key value(s). Use array for multiple primary keys. For composite key, each key value must be an array (column name=>column value).
	 * @param array|CriteriaInterface $criteria query criteria.
	 * @see Criteria
	 * @see CriteriaInterface
	 * @since v1.0
	 */
	public function deleteByPk($pkValue, $criteria = null);

	/**
	 * Deletes documents with the specified primary keys.
	 * 
	 * See `Criteria` class for detailed explanation about $criteria param.
	 * 
	 * @param mixed[] $pkValues Primary keys array
	 * @param array|CriteriaInterface $criteria query criteria.
	 * @see Criteria
	 * @see CriteriaInterface
	 * @since v1.0
	 */
	public function deleteAllByPk($pkValues, $criteria = null);

	/**
	 * Deletes all documents specified by criteria.
	 *
	 * See `Criteria` class for detailed explanation about $criteria param.
	 *
	 * @param array|CriteriaInterface $criteria query criteria.
	 * @see Criteria
	 * @see CriteriaInterface
	 * @since v1.0
	 */
	public function deleteAll($criteria = null);

	/**
	 * Repopulates this active record with the latest data.
	 *
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
