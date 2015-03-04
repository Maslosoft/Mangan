<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Maslosoft\Mangan\Validators\Traits;

/**
 * Use this trait to add validator `allowEmpty` field.
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
trait AllowEmpty
{

	/**
	 * @var boolean whether the attribute value can be null or empty. Defaults to true,
	 * meaning that if the attribute is empty, it is considered valid.
	 */
	public $allowEmpty = true;

}
