<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Maslosoft\ManganTest\Models\Meta;

use Maslosoft\Addendum\Interfaces\AnnotatedInterface;

/**
 * WithAnnotatedCollectionName
 * @Maslosoft\Mangan\Annotations\CollectionName('Foo')
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class WithAnnotatedCollectionName implements AnnotatedInterface
{

	const CollectionName = 'Foo';

}
