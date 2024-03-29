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

namespace Maslosoft\Mangan\Traits;

use Maslosoft\Addendum\Interfaces\AnnotatedInterface;
use Maslosoft\Mangan\EntityManager;
use Maslosoft\Mangan\Exceptions\ManganException;
use Maslosoft\Mangan\Interfaces\CriteriaInterface;
use Maslosoft\Mangan\Interfaces\EntityManagerInterface;
use Maslosoft\Mangan\Modifier;
use MongoDB\Collection;

/**
 * This trait contains same methods as `EntityManagerInterface`, and it forwards
 * them to concrete Entity Manager class. Entity manager used by this trait
 * can be defined via EntityManager annotation.
 *
 * This is useful to create Active Document
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
	 * 
	 * Any filtered out properties will be removed as well.
	 *
	 * The record is inserted as a documnent into the database collection, if exists it will be replaced.
	 *
	 * Validation will be performed before saving the record. If the validation fails,
	 * the record will not be saved. You can call `getErrors()` to retrieve the
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
	 * Saves the current document.
	 *
	 * The document is inserted into collection if it is not already saved. The
	 * check whether to update or insert document is dony by primary key.
	 *
	 * Validation will be performed before saving the record. If the validation fails,
	 * the record will not be saved. You can call `getErrors()` to retrieve the
	 * validation errors.
	 *
	 * If the record is saved its scenario will be set to be 'update'.
	 * And if its primary key is of type of `MongoId`, it will be set after save.
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
	 * Inserts a document into the collection based on this active document attributes.
	 *
	 * Note, validation is not performed in this method. You may call `validate()` to perform the validation.
	 *
	 * After the record is inserted to DB successfully, its  scenario will be set to be 'update'.
	 * 
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
	 * Updates the document represented by this active document.
	 * All loaded attributes will be saved to the database.
	 * Note, validation is not performed in this method. You may call `validate()` to perform the validation.
	 *
	 * @param array|null $attributes list of attributes that need to be updated. Defaults to null, meaning all attributes that are loaded from DB will be saved.
	 * @return boolean whether the update is successful
	 * @throws ManganException on timeout of db operation , when safe flag is set to true
	 * @since v1.0
	 * @Ignored
	 */
	public function update(array $attributes = null): bool
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
	 * @param null       $criteria   query criteria.
	 * @param array|null $attributes list of attributes that need to be saved. Defaults to null, meaning that all attributes that are loaded from DB will be saved.
	 * @param bool       $modify     Whether tu force update/upsert document
	 * @return bool
	 * @since v1.0
	 */
	public function updateOne($criteria = null, array $attributes = null, $modify = false): bool
	{
		return $this->_getEm()->updateOne($criteria, $attributes, $modify);
	}

	/**
	 * Atomic, in-place update method.
	 *
	 * @param Modifier               $modifier updating rules to apply
	 * @param CriteriaInterface|null $criteria condition to limit updating rules
	 * @return boolean
	 * @Ignored
	 * @since v1.3.6
	 */
	public function updateAll(Modifier $modifier, CriteriaInterface $criteria = null): bool
	{
		return $this->_getEm()->updateAll($modifier, $criteria);
	}

	/**
	 * Find and modify single document atomically.
	 *
	 * By default, this function will return updated document, ie document
	 * with applied Modifier operations.
	 *
	 * To return document before applied updates, set parameter
	 * `$returnUpdated` to false.
	 *
	 * This function will raise events and signals before operation on
	 * current model.
	 *
	 * The events and signals after operation will be performed
	 * on the returned model, depending on `$returnUpdated` parameter.
	 *
	 * @param array|CriteriaInterface $criteria
	 * @param Modifier                $modifier
	 * @param bool                    $returnUpdated
	 * @return AnnotatedInterface|null
	 */
	public function findAndModify($criteria, Modifier $modifier, $returnUpdated = true): ?AnnotatedInterface
	{
		return $this->_getEm()->findAndModify($criteria, $modifier, $returnUpdated);
	}

	/**
	 * Deletes the database document corresponding to this `Document`.
	 * @return boolean whether the deletion is successful.
	 * @throws ManganException if the record is new
	 * @since v1.0
	 * @Ignored
	 */
	public function delete(): bool
	{
		return $this->_getEm()->delete();
	}

	/**
	 * Deletes document with the specified primary key.
	 * 
	 * Additional `$criteria` can be used to filter out which document should be deleted.
	 *
	 * See `find()` for detailed explanation about `$criteria`.
	 *
	 * @param mixed $pkValue primary key value(s). Use array for multiple primary keys. For composite key, each key value must be an array (column name=>column value).
	 * @param array|CriteriaInterface $criteria query criteria.
	 * @since v1.0
	 * @Ignored
	 */
	public function deleteByPk($pkValue, $criteria = null): bool
	{
		return $this->_getEm()->deleteByPk($pkValue, $criteria);
	}

	/**
	 * Deletes documents with the specified primary keys.
	 * 
	 * Additional `$criteria` can be used to filter out which documents should be deleted.
	 *
	 * See `find()` for detailed explanation about `$criteria`.
	 *
	 * @param mixed[] $pkValues Primary keys array
	 * @param array|CriteriaInterface $criteria query criteria.
	 * @since v1.0
	 * @Ignored
	 */
	public function deleteAllByPk($pkValues, $criteria = null): bool
	{
		return $this->_getEm()->deleteAllByPk($pkValues, $criteria);
	}

	/**
	 * Deletes documents with the specified criteria.
	 *
	 * Optional `$criteria` can be used to filter out which documents should be deleted.
	 *
	 * See `find()` for detailed explanation about $criteria.
	 *
	 * @param array|CriteriaInterface $criteria query criteria.
	 * @since v1.0
	 * @Ignored
	 */
	public function deleteAll($criteria = null): bool
	{
		return $this->_getEm()->deleteAll($criteria);
	}

	/**
	 * Deletes one document with the specified primary keys.
	 *
	 * Optional `$criteria` can be used to filter out which document should be deleted.
	 *
	 * **Does not raise `beforeDelete` event**
	 *
	 * See `find()` for detailed explanation about $criteria
	 * @param array|CriteriaInterface $criteria query criteria.
	 * @since v1.0
	 * @Ignored
	 */
	public function deleteOne($criteria = null): bool
	{
		return $this->_getEm()->deleteOne($criteria);
	}

	/**
	 * Repopulates this active document with the latest data.
	 *
	 * @return boolean whether the row still exists in the database. If true, the latest data will be populated to this active record.
	 * @since v1.0
	 * @Ignored
	 */
	public function refresh(): bool
	{
		return $this->_getEm()->refresh();
	}

	/**
	 * Get working mongo collection instance.
	 *
	 * Should not be called manually in most cases.
	 *
	 * @return Collection
	 * @Ignored
	 */
	public function getCollection(): Collection
	{
		return $this->_getEm()->getCollection();
	}

	/**
	 * Get entity manager instance
	 * @return EntityManagerInterface|EntityManager
	 */
	private function _getEm(): EntityManager|EntityManagerInterface
	{
		if (null === $this->_em)
		{
			$this->_em = EntityManager::create($this);
		}
		return $this->_em;
	}

}
