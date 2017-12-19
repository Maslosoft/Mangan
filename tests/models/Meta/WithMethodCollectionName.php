<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Maslosoft\ManganTest\Models\Meta;

use Maslosoft\Mangan\Interfaces\ModelInterface;
use Maslosoft\Mangan\Interfaces\CollectionNameInterface;

/**
 * WithMethodCollectionName
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class WithMethodCollectionName implements ModelInterface, CollectionNameInterface
{

	const CollectionName = 'Rabarbar';

	public function getCollectionName()
	{
		return self::CollectionName;
	}

}
