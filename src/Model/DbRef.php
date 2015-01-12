<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Maslosoft\Mangan\Model;

use Maslosoft\Addendum\Interfaces\IAnnotated;

/**
 * DbRef
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class DbRef implements IAnnotated
{

	/**
	 * Referenced object class name
	 * @var string
	 */
	public $class = '';

	/**
	 * Primary key to retrieve object
	 * @var ObjectId|mixed|mixed[]
	 */
	public $pk = null;

}
