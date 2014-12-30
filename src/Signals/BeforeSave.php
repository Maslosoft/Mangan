<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Maslosoft\Mangan\Signals;

use Maslosoft\Addendum\Interfaces\IAnnotated;
use Maslosoft\Signals\ISignal;

/**
 * BeforeSave
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class BeforeSave implements ISignal
{

	/**
	 * Saved document
	 * @var IAnnotated
	 */
	public $model = null;

	/**
	 * Constructor
	 * @param IAnnotated $model
	 */
	public function __construct(IAnnotated $model)
	{
		$this->model = $model;
	}

}
