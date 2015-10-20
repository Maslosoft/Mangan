<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Maslosoft\Mangan\Validators\Traits;

/**
 * SkipOnError
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
trait SkipOnError
{

	/**
	 * @var boolean whether this validation rule should be skipped if when there is already a validation
	 * error for the current attribute. Defaults to true.
	 * @since 1.1.1
	 */
	public $skipOnError = true;

}
