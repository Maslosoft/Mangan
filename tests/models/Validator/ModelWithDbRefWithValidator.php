<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Maslosoft\ManganTest\Models\Validator;

use Maslosoft\Addendum\Interfaces\AnnotatedInterface;

/**
 * ModelWithDbRefWithValidator
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class ModelWithDbRefWithValidator extends AbstractValidatedModel
{

	/**
	 * @DbRef(EmbeddedModelWithValidator)
	 * @var EmbeddedModelWithValidator
	 */
	public $address = null;

}
