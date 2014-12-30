<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Maslosoft\Mangan;

use Maslosoft\Addendum\Interfaces\IAnnotated;
use Maslosoft\Mangan\Events\Event;
use Maslosoft\Mangan\Events\EventDispatcher;
use Maslosoft\Mangan\Events\ModelEvent;
use Maslosoft\Mangan\Helpers\CollectionNamer;
use Maslosoft\Mangan\Interfaces\IScenarios;
use Maslosoft\Mangan\Meta\ManganMeta;
use Maslosoft\Mangan\Options\EntityOptions;
use Maslosoft\Mangan\Signals\AfterSave;
use Maslosoft\Mangan\Transformers\FromDocument;
use Maslosoft\Signals\Signal;
use MongoCollection;
use MongoException;
use MongoId;
use SebastianBergmann\GlobalState\Exception;

/**
 * EntityManager
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class EntityManager
{

	const EventAfterSave = 'afterSave';
	const EventBeforeSave = 'beforeSave';

	/**
	 * Model
	 * @var IAnnotated
	 */
	public $model = null;

	/**
	 *
	 * @var EventDispatcher
	 */
	public $ed = null;

	/**
	 *
	 * @var 
	 */
	public $meta = null;

	/**
	 * Options
	 * @var EntityOptions
	 */
	public $options = null;

	/**
	 * Current collection name
	 * @var string
	 */
	public $collectionName = '';

	/**
	 * Current collection
	 * @var MongoCollection
	 */
	private $_collection = null;

	/**
	 * Model class name
	 * @var string
	 */
	private $_class = '';
	private $_db = null;

	public function __construct(IAnnotated $model)
	{
		$this->model = $model;
		$this->_class = get_class($model);
		$this->options = new EntityOptions($model);
		$this->collectionName = CollectionNamer::nameCollection($model);
		$this->meta = ManganMeta::create($model);
		$mangan = new Mangan($this->meta->type()->connectionId? : Mangan::DefaultConnectionId);
		if (!$this->collectionName)
		{
			throw new ManganException(sprintf('Invalid collection name for model: `%s`', $this->meta->type()->name));
		}
		$this->_collection = new MongoCollection($mangan->getDbInstance(), $this->collectionName);
	}

	public function __set($name, $value)
	{
		;
	}

	public function save()
	{
		
	}

	public function insert()
	{
		if ($this->_beforeSave())
		{
			$rawData = FromDocument::toRawArray($this->model);

			try
			{
				$result = $this->_collection->insert($rawData, $this->options->getSaveOptions());
			}
			catch (\Exception $e)
			{
				throw $e;
			}
			// strict comparison needed
			if ($result !== false)
			{
				$this->_afterSave();
				ScenarioManager::setScenario($this->model, IScenarios::Update);
				(new Signal)->emit(new AfterSave($this->model));
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
		if (Event::hasHandler($this->model, self::EventBeforeSave))
		{
			$event = new ModelEvent($this);
			Event::trigger($this->model, self::EventBeforeSave, $event);
			return $event->isValid;
		}
		else
		{
			return true;
		}
	}

	private function _afterSave()
	{
		if (Event::hasHandler($this->model, self::EventAfterSave))
		{
			Event::trigger($this->model, self::EventAfterSave);
		}
	}

	public function getCollection()
	{
		return $this->_collection;
	}
}
