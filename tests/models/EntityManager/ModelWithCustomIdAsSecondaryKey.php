<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Maslosoft\ManganTest\Models\EntityManager;

use Maslosoft\Addendum\Interfaces\AnnotatedInterface;

/**
 * ModelWithCustomIdAsSecondaryKey
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class ModelWithCustomIdAsSecondaryKey implements AnnotatedInterface
{

	public $id = '';
	public $name = '';

}
