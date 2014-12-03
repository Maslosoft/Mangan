<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Maslosoft\Mangan\Annotations;

/**
 * CollectionName
 * Use this annotation to set custom collection name
 * @template CollectionName('${name}')
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class CollectionNameAnnotation
{
	public $value;

	public function init()
	{
		$this->_entity->collectionName = $this->value;
	}
}
