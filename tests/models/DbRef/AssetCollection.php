<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Maslosoft\ManganTest\Models\DbRef;

use Maslosoft\Mangan\Document;

/**
 * AssetCollection
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class AssetCollection extends Document
{

	/**
	 * @Label('Collection title')
	 * @I18N
	 * @SafeValidator
	 * @var string
	 */
	public $title = '';

	/**
	 * @Label('Collection description')
	 * @I18N
	 * @SafeValidator
	 * @var string
	 */
	public $description = '';

	/**
	 * @Label('Groups count')
	 * @var int
	 */
	public $groupCount = 0;

	/**
	 * @Label('Assets count')
	 * @var int
	 */
	public $assetsCount = 0;

	/**
	 * @DbRefArray(AssetGroup)
	 * @var AssetGroup[]
	 */
	public $items = [];

}
