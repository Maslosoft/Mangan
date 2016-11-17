<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Maslosoft\Mangan\Traits\DataProvider;

/**
 * DataTrait
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
trait DataTrait
{

	private $data = null;

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

	/**
	 * Manually set data. This is for special cases only,
	 * usually should not be used.
	 * 
	 * @param array $data
	 */
	public function setData($data)
	{
		$this->data = $data;
	}

	abstract protected function fetchData();
}
