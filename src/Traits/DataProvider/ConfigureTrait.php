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

	protected function configure($config)
	{
		// Merge criteria from configuration
		if (isset($config['criteria']))
		{
			$this->getCriteria()->mergeWith($config['criteria']);
			unset($config['criteria']);
		}

		// Merge limit from configuration
		if (isset($config['limit']) && $config['limit'] > 0)
		{
			$this->getCriteria()->setLimit($config['limit']);
			unset($config['limit']);
		}

		// Merge sorting from configuration
		if (isset($config['sort']))
		{
			// Apply default sorting if criteria does not have sort configured
			if (isset($config['sort']['defaultOrder']) && empty($this->getCriteria()->getSort()))
			{
				$this->getCriteria()->setSort($config['sort']['defaultOrder']);
			}
			unset($config['sort']);
		}

		if (!$this->getCriteria()->getSelect())
		{
			$fields = array_keys(ManganMeta::create($this->model)->fields());
			$selected = array_fill_keys($fields, true);
			$this->getCriteria()->setSelect($selected);
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
