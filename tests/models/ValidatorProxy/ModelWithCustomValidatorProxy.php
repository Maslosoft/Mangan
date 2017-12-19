<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Maslosoft\ManganTest\Models\ValidatorProxy;

use Maslosoft\Addendum\Interfaces\AnnotatedInterface;

/**
 * ModelWithCustomValidatorProxy
 * @ConnectionId('custom-validators')
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class ModelWithCustomValidatorProxy implements AnnotatedInterface
{

	/**
	 * Should use custom required validator
	 * from connection `custom-validators`
	 * @RequiredValidator
	 * @see RequiredValidator
	 * @var string
	 */
	public $login = '';

}
