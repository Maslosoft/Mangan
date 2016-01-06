<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Maslosoft\Mangan\Decorators;

use Maslosoft\Addendum\Interfaces\AnnotatedInterface;
use Maslosoft\Mangan\Finder;
use Maslosoft\Mangan\Interfaces\CriteriaInterface;

/**
 * RelatedArrayDecorator
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class RelatedArrayDecorator extends RelatedDecorator
{

	protected function find(AnnotatedInterface $relModel, CriteriaInterface $criteria)
	{
		return (new Finder($relModel))->findAll($criteria);
	}

}
