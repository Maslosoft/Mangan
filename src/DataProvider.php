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

use CPagination;
use Maslosoft\Addendum\Interfaces\AnnotatedInterface;
use Maslosoft\Mangan\Exceptions\ManganException;
use Maslosoft\Mangan\Interfaces\CriteriaInterface;
use Maslosoft\Mangan\Interfaces\DataProviderInterface;
use Maslosoft\Mangan\Interfaces\FinderInterface;
use Maslosoft\Mangan\Interfaces\SortInterface;
use Maslosoft\Mangan\Interfaces\WithCriteriaInterface;
use Maslosoft\Mangan\Meta\ManganMeta;

/**
 * Mongo document data provider
 *
 * Implements a data provider based on Document.
 *
 * DataProvider provides data in terms of Document objects which are
 * of class {@link modelClass}. It uses the AR {@link CActiveRecord::findAll} method
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
	 * @deprecated since version number
	 * @var string
	 */
	public static $CLS = __CLASS__;

	/**
	 * @var string the name of key field. Defaults to '_id', as a mongo default document primary key.
	 * @since v1.0
	 */
	public $keyField;

	/**
	 * @var string the primary ActiveRecord class name. The {@link getData()} method
	 * will return a list of objects of this class.
	 * @since v1.0
	 */
	public $modelClass;

	/**
	 * @var Document the AR finder instance (e.g. <code>Post::model()</code>).
	 * This property can be set by passing the finder instance as the first parameter
	 * to the constructor.
	 * @since v1.0
	 */
	public $model;

	/**
	 * Finder instance
	 * @var FinderInterface
	 */
	private $_finder = null;

	/**
	 * @var CriteriaInterface
	 */
	private $_criteria;

	/**
	 * @var SortInterface
	 */
	private $_sort;
	private $_data = null;
	private $_totalItemCount = null;

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
			$this->modelClass = $modelClass;
			$this->model = new $modelClass;
		}
		elseif (is_object($modelClass))
		{
			$this->modelClass = get_class($modelClass);
			$this->model = $modelClass;
		}
		else
		{
			throw new ManganException('Invalid model type for ' . __CLASS__);
		}

		$this->_finder = Finder::create($this->model);
		if ($this->model instanceof WithCriteriaInterface)
		{
			$this->_criteria = $this->model->getDbCriteria();
		}
		else
		{
			$this->_criteria = new Criteria();
		}
		if (isset($config['criteria']))
		{
			$this->_criteria->mergeWith($config['criteria']);
			unset($config['criteria']);
		}

		if (!$this->_criteria->getSelect())
		{
			$fields = array_keys(ManganMeta::create($this->model)->fields());
			$fields = array_fill_keys($fields, true);
			$this->_criteria->setSelect($fields);
		}

		foreach ($config as $key => $value)
		{
			$this->$key = $value;
		}

		if ($this->keyField !== null)
		{
			if (is_array($this->keyField))
			{
				throw new ManganException('This DataProvider cannot handle multi-field primary key.');
			}
		}
		else
		{
			$this->keyField = '_id';
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
	 * @return array the query criteria
	 * @since v1.0
	 */
	public function getCriteria()
	{
		return $this->_criteria;
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
			$this->_criteria = new Criteria($criteria);
		}
		elseif ($criteria instanceof CriteriaInterface)
		{
			$this->_criteria = $criteria;
		}
	}

	/**
	 * Returns the sort object.
	 * @return Sort the sorting object. If this is false, it means the sorting is disabled.
	 */
	public function getSort()
	{
		if ($this->_sort === null)
		{
			$this->_sort = new Sort;
			$this->_sort->setModel($this->model);
		}
		return $this->_sort;
	}

	/**
	 * Set sort
	 * @param SortInterface $sort
	 * @return DataProvider
	 */
	public function setSort(SortInterface $sort)
	{
		$this->_sort = $sort;
		$this->_sort->setModel($this->model);
		return $this;
	}

	/**
	 * Returns the pagination object.
	 * @param string $className the pagination object class name. Parameter is available since version 1.1.13.
	 * @return CPagination|false the pagination object. If this is false, it means the pagination is disabled.
	 */
	public function getPagination($className = 'CPagination')
	{
		return false;
//		if($this->_pagination===null)
//		{
//			$this->_pagination=new $className;
//			if(($id=$this->getId())!='')
//				$this->_pagination->pageVar=$id.'_page';
//		}
//		return $this->_pagination;
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
		if ($this->_totalItemCount === null)
		{
			$this->_totalItemCount = $this->_finder->count($this->_criteria);
		}
		return $this->_totalItemCount;
	}

	/**
	 * Fetches the data from the persistent data storage.
	 * @return Document[]|Cursor list of data items
	 * @since v1.0
	 */
	protected function fetchData()
	{
		if (($pagination = $this->getPagination()) !== false)
		{
			$pagination->setItemCount($this->getTotalItemCount());

			$this->_criteria->setLimit($pagination->getLimit());
			$this->_criteria->setOffset($pagination->getOffset());
		}

		$sort = $this->getSort();
		if ($sort->isSorted())
		{
			$this->_criteria->setSort($sort);
		}

		return $this->_finder->findAll($this->_criteria);
	}

	/**
	 * Returns the data items currently available, ensures that result is at leas empty array
	 * @param boolean $refresh whether the data should be re-fetched from persistent storage.
	 * @return array the list of data items currently available in this data provider.
	 */
	public function getData($refresh = false)
	{
		if ($this->_data === null || $refresh)
		{
			$this->_data = $this->fetchData();
		}
		if ($this->_data === null)
		{
			return [];
		}
		return $this->_data;
	}

}
