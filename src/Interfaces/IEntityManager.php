<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Maslosoft\Mangan\Interfaces;

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

	public function insert();

	/**
	 * Updates the row represented by this active record.
	 * All loaded attributes will be saved to the database.
	 * Note, validation is not performed in this method. You may call {@link validate} to perform the validation.
	 * @param array $attributes list of attributes that need to be saved. Defaults to null,
	 * meaning all attributes that are loaded from DB will be saved.
	 * @param boolean modify if set true only selected attributes will be replaced, and not
	 * the whole document
	 * @return boolean whether the update is successful
	 * @throws MongoException if the record is new
	 * @throws MongoException on fail of update
	 * @throws MongoException on timeout of db operation , when safe flag is set to true
	 * @since v1.0
	 */
	public function update(array $attributes = null, $modify = false);

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
	 * @param mixed $pk primary key value(s). Use array for multiple primary keys. For composite key, each key value must be an array (column name=>column value).
	 * @param array|Criteria $criteria query criteria.
	 * @since v1.0
	 */
	public function deleteByPk($pk, $criteria = null);

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
