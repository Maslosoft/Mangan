<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Maslosoft\Mangan\Validators\Traits;

/**
 * Use this trait to add `strict` field
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
trait Strict
{

	/**
	 * When this is true, the attribute value and type must both match.
	 * Defaults to false, meaning only the value needs to be matched.
	 * @var bool Whether the comparison is strict.
	 */
	public $strict = false;

}
