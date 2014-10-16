<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Maslosoft\Mangan;

use Maslosoft\Mangan\Events\ModelEvent;
use Maslosoft\Mangan\Options\EntityOptions;
use Maslosoft\Mangan\Signals\AfterSave;
use Maslosoft\Mangan\Transformers\ToRawArray;
use Maslosoft\Signals\Signal;
use MongoCollection;
use MongoException;
use MongoId;
use SebastianBergmann\GlobalState\Exception;
use Yii;

/**
 * EntityManager
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class EntityManager
{

	/**
	 * Model
	 * @var Document
	 */
	public $model = null;

	/**
	 * Options
	 * @var EntityOptions
	 */
	public $options = null;

	/**
	 * Current collection
	 * @var MongoCollection
	 */
	public $collection = null;

	/**
	 * Model class name
	 * @var string
	 */
	private $_class = '';

	public function __construct(Document $model)
	{
		$this->model = $model;
		$this->_class = get_class($model);
		$this->options = new EntityOptions($model);
	}

	public function save()
	{

	}

	public function insert(array $attributes = null)
	{
		if (!$this->getIsNewRecord())
		{
			throw new MongoException(Yii::t('yii', 'The Document cannot be inserted to database because it is not new.'));
		}
		if ($this->_beforeSave())
		{
			Yii::trace($this->_class . '.insert()', 'Maslosoft.Mangan.EntityManager');

			// Ensure that id is set
			if (!$this->model->getId())
			{
				$this->model->setId(new MongoId);
			}
			$rawData = (array)new ToRawArray($this->model);

			// filter attributes if set in param
			if ($attributes !== null)
			{
				// Ensure id
				$attributes['_id'] = true;
				foreach ($rawData as $key => $value)
				{
					if (!in_array($key, $attributes))
					{
						unset($rawData[$key]);
					}
				}
			}
			// Check for individual pk
			$pk = $this->primaryKey();
			if ('_id' !== $pk && 0 !== $this->countByAttributes([$pk => $this->{$pk}]))
			{
				throw new MongoException('The Document cannot be inserted because the primary key already exists.');
			}

			try
			{
				$result = $this->getCollection()->insert($rawData, $this->options->getSaveOptions());
			}
			catch (Exception $e)
			{
				throw $e;
			}

			if ($result !== false)
			{ // strict comparison needed
				$this->_id = $rawData['_id'];
				$this->_afterSave();
				$this->setIsNewRecord(false);
				$this->setScenario('update');
				(new Signal)->emit(new AfterSave($this));
				return true;
			}
			throw new MongoException('Can\t save the document to disk, or attempting to save an empty document.');
		}
		return false;
	}

	public function update()
	{
		
	}

	private function _beforeSave()
	{
		if ($this->model->hasEventHandler('onBeforeSave'))
		{
			$event = new ModelEvent($this);
			$this->model->onBeforeSave($event);
			return $event->isValid;
		}
		else
		{
			return true;
		}
	}

	private function _afterSave()
	{
		if ($this->model->hasEventHandler('onAfterSave'))
		{
			$this->model->onAfterSave(new ModelEvent($this));
		}
	}
}
