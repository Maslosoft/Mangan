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

namespace Maslosoft\Mangan;

use Maslosoft\Addendum\Interfaces\AnnotatedInterface;
use Maslosoft\EmbeDi\EmbeDi;
use Maslosoft\Mangan\Exceptions\ManganException;
use Maslosoft\Mangan\Interfaces\Criteria\DecoratableInterface;
use Maslosoft\Mangan\Interfaces\Criteria\LimitableInterface;
use Maslosoft\Mangan\Interfaces\CriteriaInterface;
use Maslosoft\Mangan\Interfaces\DataProviderInterface;
use Maslosoft\Mangan\Interfaces\FinderInterface;
use Maslosoft\Mangan\Interfaces\PaginationInterface;
use Maslosoft\Mangan\Interfaces\SortInterface;
use Maslosoft\Mangan\Interfaces\WithCriteriaInterface;
use Maslosoft\Mangan\Meta\ManganMeta;

/**
 * Mongo document data provider
 *
 * Implements a data provider based on Document.
 *
 * DataProvider provides data in terms of Document objects which are
 * of class {@link modelClass}. It uses the AR {@link EntityManager::findAll} method
 * to retrieve the data from database. The {@link query} property can be used to
 * specify various query options, such as conditions, sorting, pagination, etc.
 *
 * @author Ianaré Sévi
 * @author Dariusz Górecki <darek.krk@gmail.com>
 * @author Invenzzia Group, open-source division of CleverIT company http://www.invenzzia.org
 * @copyright 2011 CleverIT http://www.cleverit.com.pl
 * @since v1.0
 */
class DataProvider implements DataProviderInterface
{

	/**
	 * Instance of model
	 * @var Document
	 * @since v1.0
	 */
	public $model;

	/**
	 * Finder instance
	 * @var FinderInterface
	 */
	private $finder = null;

	/**
	 * @var CriteriaInterface
	 */
	private $criteria;

	/**
	 * @var SortInterface
	 */
	private $sort;
	private $data = null;
	private $totalItemCount = null;

	/**
	 * Pagination instance
	 * @var PaginationInterface
	 */
	private $pagination = null;

	/**
	 * Constructor.
	 * @param mixed $modelClass the model class (e.g. 'Post') or the model finder instance
	 * (e.g. <code>Post::model()</code>, <code>Post::model()->published()</code>).
	 * @param array $config configuration (name=>value) to be applied as the initial property values of this class.
	 * @since v1.0
	 */
	public function __construct($modelClass, $config = [])
	{
		if (is_string($modelClass))
		{
			$this->model = new $modelClass;
		}
		elseif (is_object($modelClass))
		{
			$this->model = $modelClass;
		}
		else
		{
			throw new ManganException('Invalid model type for ' . __CLASS__);
		}

		$this->finder = Finder::create($this->model);
		if ($this->model instanceof WithCriteriaInterface)
		{
			$this->criteria = $this->model->getDbCriteria();
		}
		else
		{
			$this->criteria = new Criteria();
		}

		// Merge criteria from configuration
		if (isset($config['criteria']))
		{
			$this->criteria->mergeWith($config['criteria']);
			unset($config['criteria']);
		}

		// Merge limit from configuration
		if (isset($config['limit']) && $config['limit'] > 0)
		{
			$this->criteria->setLimit($config['limit']);
			unset($config['limit']);
		}

		// Merge sorting from configuration
		if (isset($config['sort']))
		{
			// Apply default sorting if criteria does not have sort configured
			if (isset($config['sort']['defaultOrder']) && empty($this->criteria->getSort()))
			{
				$this->criteria->setSort($config['sort']['defaultOrder']);
			}
			unset($config['sort']);
		}

		if (!$this->criteria->getSelect())
		{
			$fields = array_keys(ManganMeta::create($this->model)->fields());
			$fields = array_fill_keys($fields, true);
			$this->criteria->setSelect($fields);
		}

		foreach ($config as $key => $value)
		{
			$this->$key = $value;
		}
	}

	/**
	 * Get model used by this dataprovider
	 * @return AnnotatedInterface
	 */
	public function getModel()
	{
		return $this->model;
	}

	/**
	 * Returns the criteria.
	 * @return Criteria the query criteria
	 * @since v1.0
	 */
	public function getCriteria()
	{
		// Initialise empty criteria, so it's always available via this method call.
		if (empty($this->criteria))
		{
			$this->criteria = new Criteria;
		}
		return $this->criteria;
	}

	/**
	 * Sets the query criteria.
	 * @param CriteriaInterface|array $criteria the query criteria. Array representing the MongoDB query criteria.
	 * @since v1.0
	 */
	public function setCriteria($criteria)
	{
		if (is_array($criteria))
		{
			$this->criteria = new Criteria($criteria);
		}
		elseif ($criteria instanceof CriteriaInterface)
		{
			$this->criteria = $criteria;
		}
		if ($this->criteria instanceof DecoratableInterface)
		{
			$this->criteria->decorateWith($this->getModel());
		}
	}

	/**
	 * Returns the sort object.
	 * @return Sort the sorting object. If this is false, it means the sorting is disabled.
	 */
	public function getSort()
	{
		if ($this->sort === null)
		{
			$this->sort = new Sort;
			$this->sort->setModel($this->model);
		}
		return $this->sort;
	}

	/**
	 * Set sort
	 * @param SortInterface $sort
	 * @return DataProvider
	 */
	public function setSort(SortInterface $sort)
	{
		$this->sort = $sort;
		$this->sort->setModel($this->model);
		return $this;
	}

	/**
	 * Returns the pagination object.
	 * @param string $className the pagination object class name, use this param to override default pagination class.
	 * @return Pagination|false the pagination object. If this is false, it means the pagination is disabled.
	 */
	public function getPagination($className = Pagination::class)
	{
		if ($this->pagination === false)
		{
			return false;
		}
		if ($this->pagination === null)
		{
			$this->pagination = new $className;
		}

		// FIXME: Attach pagination options if it's array.
		// It might be array, when configured via constructor
		if (is_array($this->pagination))
		{
			if (empty($this->pagination['class']))
			{
				$this->pagination['class'] = $className;
			}
			$this->pagination = EmbeDi::fly()->apply($this->pagination);
		}
		return $this->pagination;
	}

	/**
	 * Returns the number of data items in the current page.
	 * This is equivalent to <code>count($provider->getData())</code>.
	 * When {@link pagination} is set false, this returns the same value as {@link totalItemCount}.
	 * @param boolean $refresh whether the number of data items should be re-calculated.
	 * @return integer the number of data items in the current page.
	 */
	public function getItemCount($refresh = false)
	{
		return count($this->getData($refresh));
	}

	/**
	 * Returns the total number of data items.
	 * When {@link pagination} is set false, this returns the same value as {@link itemCount}.
	 * @return integer total number of possible data items.
	 */
	public function getTotalItemCount()
	{
		if ($this->totalItemCount === null)
		{
			$this->totalItemCount = $this->finder->count($this->criteria);
		}
		return $this->totalItemCount;
	}

	/**
	 * Fetches the data from the persistent data storage.
	 * @return Document[]|Cursor list of data items
	 * @since v1.0
	 */
	protected function fetchData()
	{
		$pagination = $this->getPagination();
		if ($pagination !== false && $this->criteria instanceof LimitableInterface)
		{
			$pagination->setCount($this->getTotalItemCount());
			$pagination->apply($this->criteria);
		}

		$sort = $this->getSort();
		if ($sort->isSorted())
		{
			$this->criteria->setSort($sort);
		}

		return $this->finder->findAll($this->criteria);
	}

	/**
	 * Returns the data items currently available, ensures that result is at leas empty array
	 * @param boolean $refresh whether the data should be re-fetched from persistent storage.
	 * @return array the list of data items currently available in this data provider.
	 */
	public function getData($refresh = false)
	{
		if ($this->data === null || $refresh)
		{
			$this->data = $this->fetchData();
		}
		if ($this->data === null)
		{
			return [];
		}
		return $this->data;
	}

}
