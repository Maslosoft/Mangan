<?php

namespace Maslosoft\ManganTest\Models\NonHomogenous;

use Maslosoft\Mangan\Document;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * ModelOne
 * @CollectionName('NonHomogeneous')
 * @Homogeneous(false)
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class ModelTwo extends Document
{

	public $name = '';
	public $title = '';
	public $type = 0;
}
