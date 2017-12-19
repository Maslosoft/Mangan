<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Maslosoft\ManganTest\Models\DbRef;

use Maslosoft\Mangan\Document;
use Maslosoft\Mangan\Model\Image;

/**
 * PageAsset
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class PageAsset extends Document
{

	use \Maslosoft\Mangan\Traits\Model\WithParentTrait;

	/**
	 * @Label('File name')
	 * @Persistent(false)
	 * @var string
	 */
	public $filename = '';

	/**
	 * @Embedded(Image)
	 * @var Image
	 */
	public $file = null;

	/**
	 * @Label('File name')
	 * @Persistent(false)
	 * @var string
	 */
	public $basename = '';

	/**
	 * @Persistent(false)
	 * @var string
	 */
	public $relativeName = '';

	/**
	 * @Label('Icon')
	 * @Readonly
	 * @Persistent(false)
	 * @Renderer('Icon')
	 * @var string
	 */
	public $icon = '';

	/**
	 * @Label("It's image")
	 * @Readonly
	 * @Persistent(false)
	 * @var bool
	 */
	public $isImage = false;

	/**
	 * @Label('Icon size')
	 * @Persistent(false)
	 * @var int
	 */
	public $iconSize = 512;

	/**
	 * @Label('Folder')
	 * @Persistent(false)
	 * @var string
	 */
	public $path = '';

	/**
	 * Url to download this asset
	 * @Label('URL')
	 * @Persistent(false)
	 * @Readonly
	 * @var string
	 */
	public $url = '';

	/**
	 * @Label('File type')
	 * @Persistent(false)
	 * @Readonly
	 * @var string
	 */
	public $type = '';

	/**
	 * @Label('Deleted')
	 * @Persistent(false)
	 * @deprecated since version number
	 * TODO Check if it is used somewhere
	 * @var string
	 */
	public $deleted = false;

	/**
	 * @Label('File title')
	 * @I18N
	 * @SafeValidator
	 * @var string
	 */
	public $title = '';

	/**
	 * @Label('File description')
	 * @I18N
	 * @SafeValidator
	 * @Renderer('TextArea')
	 * @var type
	 */
	public $description = '';

}
