<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Maslosoft\Mangan;

use Maslosoft\Addendum\Interfaces\IAnnotated;
use Maslosoft\Mangan\Interfaces\IModel;

/**
 * Validator
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class Validator
{

	/**
	 * Model instance
	 * @var IModel
	 */
	private $_model = null;

	public function __construct(IAnnotated $model)
	{
		$this->_model = $model;
	}

	public function validate()
	{
		return true;
	}

}
