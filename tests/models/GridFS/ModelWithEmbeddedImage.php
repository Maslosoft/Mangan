<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Maslosoft\ManganTest\Models\GridFS;

use Maslosoft\Mangan\Document;
use Maslosoft\Mangan\Model\Image;

/**
 * ModelWithEmbeddedFile
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class ModelWithEmbeddedImage extends Document
{

	/**
	 *
	 * @var string
	 */
	public $title = '';

	/**
	 * @Embedded(Image)
	 * @var Image
	 */
	public $file = null;

}
