<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Maslosoft\Mangan\Interfaces\Criteria;

use Maslosoft\Addendum\Interfaces\AnnotatedInterface;
use Maslosoft\Mangan\Interfaces\CriteriaInterface;

/**
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
interface DecoratableInterface
{

	/**
	 * Decorate and sanitize criteria with provided model.
	 *
	 * @param AnnotatedInterface $model Model to use for decorators and sanitizer when creating conditions. If null no decorators will be used. If model is provided it's sanitizers and decorators will be used.
	 * @return CriteriaInterface
	 */
	public function decorateWith($model);
}
