<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Maslosoft\ManganTest\Models\Embedded;

use Maslosoft\Mangan\Document;
use Maslosoft\ManganTest\Models\ModelWithI18N;

/**
 * WithEmbeddedI18NModel
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class WithEmbeddedArrayI18NModel extends Document
{

	/**
	 * @EmbeddedArray(Maslosoft\ManganTest\Models\ModelWithI18N)
	 * @var ModelWithI18N[]
	 */
	public $pages = [];

	/**
	 * @Embedded
	 * @var ModelWithI18N[]
	 */
	public $page = null;

}
