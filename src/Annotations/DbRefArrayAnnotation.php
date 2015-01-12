<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Maslosoft\Mangan\Annotations;

use Maslosoft\Mangan\Decorators\DbRefArrayDecorator;

/**
 * DB reference array annotation
 * @template DbRefArray(${class}, ${updatable})
 * @Target('property')
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class DbRefArrayAnnotation extends DbRefAnnotation
{

	public function init()
	{
		$refMeta = $this->_createMeta();
		$refMeta->single = false;
		$refMeta->isArray = true;
		$this->_entity->dbRef = $refMeta;
		$this->_entity->decorators[] = DbRefArrayDecorator::class;
	}

}
