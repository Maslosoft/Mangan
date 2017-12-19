<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Maslosoft\ManganTest\Models\Validator;

use Maslosoft\Mangan\Document;

/**
 * DocumentWithRequiredValidator
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class DocumentWithRequiredValidator extends Document
{

	/**
	 * @RequiredValidator
	 * @var string
	 */
	public $login = '';

}
