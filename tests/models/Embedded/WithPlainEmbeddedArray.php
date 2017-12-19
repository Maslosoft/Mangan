<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Maslosoft\ManganTest\Models\Embedded;

use Maslosoft\Addendum\Interfaces\AnnotatedInterface;

/**
 * WithPlainEmbeddedArray
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class WithPlainEmbeddedArray implements AnnotatedInterface
{
	public $_id;
	/**
	 * @Maslosoft\Mangan\Annotations\EmbeddedArray(Maslosoft\ManganTest\Models\Plain\SimplePlainEmbedded)
	 * @var SimplePlainEmbedded
	 */
	public $stats = [];

	public $title = '';
}
