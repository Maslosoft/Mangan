<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Maslosoft\Mangan\Meta;

/**
 * ManganPropertyAnnotation
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
abstract class ManganPropertyAnnotation extends ManganAnnotation
{

	/**
	 * Annotatins entity, it can be either class, property, or method
	 * Its conrete annotation implementation responsibility to decide what to do with it.
	 * @var DocumentPropertyMeta
	 */
	protected $_entity = null;

}
