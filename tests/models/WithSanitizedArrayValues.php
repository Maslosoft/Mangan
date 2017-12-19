<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Maslosoft\ManganTest\Models;

use Maslosoft\Mangan\Document;

/**
 * WithSanitizedArrayValues
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class WithSanitizedArrayValues extends Document
{

	/**
	 * @SanitizerArray('StringSanitizer')
	 * @var string[]
	 */
	public $title = [];

	/**
	 * @SanitizerArray('IntegerSanitizer')
	 * @var int[]
	 */
	public $goals = [];

	/**
	 * @SanitizerArray('BooleanSanitizer')
	 * @var bool[]
	 */
	public $shots = [];

}
