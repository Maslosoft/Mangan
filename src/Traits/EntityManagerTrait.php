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
use Maslosoft\Mangan\EntityManager;
use Maslosoft\Mangan\Exceptions\ManganException;
use Maslosoft\Mangan\Interfaces\CriteriaInterface;
use Maslosoft\Mangan\Interfaces\EntityManagerInterface;
use Maslosoft\Mangan\Modifier;
use MongoCollection;

/**
 * This trait contains same methods as `EntityManagerInterface`, and it forwards
 * them to concrete Entity Manager class. Entity manager used by this trait
 * can be defined via EntityManager annotation.
 *
 * This is usefull to create Active Document
 * pattern classes.
 *
 * @see EntityManagerInterface
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
trait EntityManagerTrait
{

	/**
	 * Entity manager
	 * @var EntityManagerInterface|EntityManager
	 */
	private $_em = null;

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
	public function replace($runValidation = true)
	{
		return $this->_getEm()->replace($runValidation);
	}

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
	 *
	 * @return boolean whether the saving succeeds
	 * @since v1.0
	 * @Ignored
	 */
	public function save($runValidation = true)
	{
		return $this->_getEm()->save($runValidation);
	}

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
	public function upsert($runValidation = true)
	{
		return $this->_getEm()->upsert($runValidation);
	}

	/**
	 * Inserts a row into the table based on this active record attributes.
	 * If the table's primary key is auto-incremental and is null before insertion,
	 * it will be populated with the actual value after insertion.
	 * Note, validation is not performed in this method. You may call {@link validate} to perform the validation.
	 * After the record is inserted to DB successfully, its {@link isNewRecord} property will be set false,
	 * and its {@link scenario} property will be set to be 'update'.
	 * @param AnnotatedInterface $model if want to insert different model than set in constructor
	 * @return boolean whether the attributes are valid and the record is inserted successfully.
	 * @throws ManganException if the record is not new
	 * @throws ManganException on fail of insert or insert of empty document
	 * @throws ManganException on fail of insert, when safe flag is set to true
	 * @throws ManganException on timeout of db operation , when safe flag is set to true
	 * @since v1.0
	 * @Ignored
	 */
	public function insert(AnnotatedInterface $model = null)
	{
		return $this->_getEm()->insert($model);
	}

	/**
	 * Updates the row represented by this active record.
	 * All loaded attributes will be saved to the database.
	 * Note, validation is not performed in this method. You may call {@link validate} to perform the validation.
	 * @param array $attributes list of attributes that need to be updated. Defaults to null,
	 * meaning all attributes that are loaded from DB will be saved.
	 * @return boolean whether the update is successful
	 * @throws ManganException if the record is new
	 * @throws ManganException on fail of update
	 * @throws ManganException on timeout of db operation , when safe flag is set to true
	 * @since v1.0
	 * @Ignored
	 */
	public function update(array $attributes = null)
	{
		return $this->_getEm()->update($attributes);
	}

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
	public function updateOne($criteria = null, array $attributes = null, $modify = false)
	{
		return $this->_getEm()->updateOne($criteria, $attributes, $modify);
	}

	/**
	 * Atomic, in-place update method.
	 *
	 * @since v1.3.6
	 * @param Modifier $modifier updating rules to apply
	 * @param CriteriaInterface $criteria condition to limit updating rules
	 * @return boolean|mixed[]
	 * @Ignored
	 */
	public function updateAll(Modifier $modifier, CriteriaInterface $criteria = null)
	{
		return $this->_getEm()->updateAll($modifier, $criteria);
	}

	/**
	 * Deletes the row corresponding to this Document.
	 * @return boolean whether the deletion is successful.
	 * @throws ManganException if the record is new
	 * @since v1.0
	 * @Ignored
	 */
	public function delete()
	{
		return $this->_getEm()->delete();
	}

	/**
	 * Deletes document with the specified primary key.
	 * See {@link find()} for detailed explanation about $condition and $params.
	 * @param mixed $pkValue primary key value(s). Use array for multiple primary keys. For composite key, each key value must be an array (column name=>column value).
	 * @param array|CriteriaInterface $criteria query criteria.
	 * @since v1.0
	 * @Ignored
	 */
	public function deleteByPk($pkValue, $criteria = null)
	{
		return $this->_getEm()->deleteByPk($pkValue, $criteria);
	}

	/**
	 * Deletes documents with the specified primary keys.
	 * See {@link find()} for detailed explanation about $condition and $params.
	 * @param mixed[] $pkValues Primary keys array
	 * @param array|CriteriaInterface $criteria query criteria.
	 * @since v1.0
	 * @Ignored
	 */
	public function deleteAllByPk($pkValues, $criteria = null)
	{
		return $this->_getEm()->deleteAllByPk($pkValues, $criteria);
	}

	/**
	 * Deletes documents with the specified criteria.
	 * See {@link find()} for detailed explanation about $condition and $params.
	 * @param array|CriteriaInterface $criteria query criteria.
	 * @since v1.0
	 * @Ignored
	 */
	public function deleteAll($criteria = null)
	{
		return $this->_getEm()->deleteAll($criteria);
	}

	/**
	 * Deletes one document with the specified primary keys.
	 * <b>Does not raise beforeDelete</b>
	 * See {@link find()} for detailed explanation about $condition and $params.
	 * @param array|CriteriaInterface $criteria query criteria.
	 * @since v1.0
	 * @Ignored
	 */
	public function deleteOne($criteria = null)
	{
		return $this->_getEm()->deleteOne($criteria);
	}

	/**
	 * Repopulates this active record with the latest data.
	 * @return boolean whether the row still exists in the database. If true, the latest data will be populated to this active record.
	 * @since v1.0
	 * @Ignored
	 */
	public function refresh()
	{
		return $this->_getEm()->refresh();
	}

	/**
	 *
	 * @return MongoCollection
	 * @Ignored
	 */
	public function getCollection()
	{
		return $this->_getEm()->getCollection();
	}

	/**
	 * Get entity manager instance
	 * @return EntityManagerInterface|EntityManager
	 */
	private function _getEm()
	{
		if (null === $this->_em)
		{
			$this->_em = EntityManager::create($this);
		}
		return $this->_em;
	}

}
