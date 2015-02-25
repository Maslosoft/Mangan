<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Maslosoft\Mangan;

/**
 * ScopeManager
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class ScopeManager
{

	/**
	 * Model instance
	 * @var IModel
	 */
	private $model = null;

	/**
	 *
	 * @var Criteria
	 */
	private $criteria = null;

	public function __construct($model)
	{
		$this->model = $model;
	}

	public function apply(Criteria &$criteria = null)
	{
		if (null === $criteria)
		{
			$criteria = new Criteria();
			return $this;
		}
		$criteria->mergeWith($this->criteria);
		return $this;
	}

	public function reset()
	{
		$this->_criteria = new Criteria();
		return $this;
	}

}
