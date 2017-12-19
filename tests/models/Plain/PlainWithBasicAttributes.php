<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Maslosoft\ManganTest\Models\Plain;

use Maslosoft\Addendum\Interfaces\AnnotatedInterface;

/**
 * PlainWithBasicAttributes
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class PlainWithBasicAttributes implements AnnotatedInterface
{
	public $_id = null;
	public $int = 23;
	public $string = 'test';
	public $bool = true;
	public $float = 0.23;
	public $array = [];
	public $null = null;

}
