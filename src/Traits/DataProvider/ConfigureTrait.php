<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Maslosoft\Mangan\Traits\DataProvider;

use Maslosoft\Addendum\Interfaces\AnnotatedInterface;
use Maslosoft\Mangan\Exceptions\ManganException;
use Maslosoft\Mangan\Interfaces\Criteria\LimitableInterface;
use Maslosoft\Mangan\Interfaces\Criteria\MergeableInterface;
use Maslosoft\Mangan\Interfaces\CriteriaAwareInterface;
use Maslosoft\Mangan\Interfaces\CriteriaInterface;
use Maslosoft\Mangan\Interfaces\PaginationInterface;
use Maslosoft\Mangan\Interfaces\SortInterface;
use Maslosoft\Mangan\Interfaces\WithCriteriaInterface;
use Maslosoft\Mangan\Meta\ManganMeta;
use Maslosoft\Mangan\Pagination;

/**
 * ConfigureTrait
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
trait ConfigureTrait
{

	protected function configure($modelClass, $config)
	{
		if (is_string($modelClass))
		{
			$this->setModel(new $modelClass);
		}
		elseif (is_object($modelClass))
		{
			$this->setModel($modelClass);
		}
		else
		{
			throw new ManganException('Invalid model type for ' . static::class);
		}

		$model = $this->getModel();

		// Set criteria from model
		$criteria = $this->getCriteria();
		if ($criteria instanceof MergeableInterface)
		{
			// NOTE: WithCriteria and CriteriaAware have just slightly different method names
			if ($model instanceof WithCriteriaInterface)
			{
				$criteria->mergeWith($model->getDbCriteria());
			}
			elseif ($model instanceof CriteriaAwareInterface)
			{
				$criteria->mergeWith($model->getCriteria());
			}
		}

		// Merge criteria from configuration
		if (isset($config['criteria']))
		{
			$criteria->mergeWith($config['criteria']);
			unset($config['criteria']);
		}

		// Merge limit from configuration
		if (isset($config['limit']) && $config['limit'] > 0)
		{
			$criteria->setLimit($config['limit']);
			unset($config['limit']);
		}

		// Merge sorting from configuration
		if (isset($config['sort']))
		{
			// Apply default sorting if criteria does not have sort configured
			$sort = $criteria->getSort();
			if (isset($config['sort']['defaultOrder']) && empty($sort))
			{
				$criteria->setSort($config['sort']['defaultOrder']);
			}
			unset($config['sort']);
		}

		if (!$criteria->getSelect())
		{
			$fields = array_keys(ManganMeta::create($model)->fields());
			$selected = array_fill_keys($fields, true);
			$criteria->setSelect($selected);
		}

		foreach ($config as $key => $value)
		{
			$this->$key = $value;
		}
	}

	/**
	 * Configure limits, sorti for fetching data
	 * @return CriteriaInterface
	 */
	protected function configureFetch()
	{
		// Setup required objects
		$sort = $this->getSort();
		$criteria = $this->getCriteria();
		$pagination = $this->getPagination();

		// Apply limits if required
		if ($pagination !== false && $criteria instanceof LimitableInterface)
		{
			$pagination->setCount($this->getTotalItemCount());
			$pagination->apply($criteria);
		}

		// Apply sort if required
		if ($sort->isSorted())
		{
			$criteria->setSort($sort);
		}
		return $criteria;
	}

	/**
	 * Returns the sort object.
	 * @return SortInterface the sorting object. If this is false, it means the sorting is disabled.
	 */
	abstract public function getSort();

	/**
	 * Returns the pagination object.
	 * @param string $className the pagination object class name, use this param to override default pagination class.
	 * @return PaginationInterface|Pagination|false the pagination object. If this is false, it means the pagination is disabled.
	 */
	abstract public function getPagination($className = Pagination::class);

	/**
	 * Returns the total number of data items.
	 * When {@link pagination} is set false, this returns the same value as {@link itemCount}.
	 * @return integer total number of possible data items.
	 */
	abstract public function getTotalItemCount();

	/**
	 * @return CriteriaInterface
	 */
	abstract public function getCriteria();

	/**
	 * @return AnnotatedInterface
	 */
	abstract public function getModel();

	/**
	 * @param $model AnnotatedInterface
	 * @return static
	 */
	abstract public function setModel(AnnotatedInterface $model);
}
