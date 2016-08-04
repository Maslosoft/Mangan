<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Maslosoft\Mangan\Traits;

use Maslosoft\Addendum\Interfaces\AnnotatedInterface;

/**
 * ModelAwareTrait
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
trait ModelAwareTrait
{

	/**
	 * Instance of model
	 * @var AnnotatedInterface
	 * @since v1.0
	 */
	public $model;

	/**
	 * Get model used by this data provider
	 * @return AnnotatedInterface
	 */
	public function getModel()
	{
		return $this->model;
	}

	/**
	 * Set model
	 * @param AnnotatedInterface $model
	 * @return static
	 */
	public function setModel(AnnotatedInterface $model)
	{
		$this->model = $model;
		return $this;
	}

}
