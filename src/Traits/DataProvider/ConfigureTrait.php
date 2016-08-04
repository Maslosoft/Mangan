<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Maslosoft\Mangan\Traits\DataProvider;

use Maslosoft\Mangan\Interfaces\CriteriaInterface;
use Maslosoft\Mangan\Meta\ManganMeta;

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
			$this->model = new $modelClass;
		}
		elseif (is_object($modelClass))
		{
			$this->model = $modelClass;
		}
		else
		{
			throw new ManganException('Invalid model type for ' . static::class);
		}


		// Set criteria from model
		$criteria = $this->getCriteria();
		if ($criteria instanceof MergeableInterface)
		{
			// NOTE: WithCriteria and CriteriaAware have just slightly different method names
			if ($this->model instanceof WithCriteriaInterface)
			{
				$criteria->mergeWith($this->model->getDbCriteria());
			}
			elseif ($this->model instanceof CriteriaAwareInterface)
			{
				$criteria->mergeWith($this->model->getCriteria());
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
			if (isset($config['sort']['defaultOrder']) && empty($this->getCriteria()->getSort()))
			{
				$criteria->setSort($config['sort']['defaultOrder']);
			}
			unset($config['sort']);
		}

		if (!$criteria->getSelect())
		{
			$fields = array_keys(ManganMeta::create($this->model)->fields());
			$selected = array_fill_keys($fields, true);
			$criteria->setSelect($selected);
		}

		foreach ($config as $key => $value)
		{
			$this->$key = $value;
		}
	}

	/**
	 * @return CriteriaInterface
	 */
	abstract public function getCriteria();
}
