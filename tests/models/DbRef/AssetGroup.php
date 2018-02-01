<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Maslosoft\ManganTest\Models\DbRef;

use Maslosoft\Mangan\Document;
use Maslosoft\Mangan\Traits\Model\WithParentTrait;

/**
 * AssetGroup
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class AssetGroup extends Document
{

	use WithParentTrait;

	/**
	 * @Label('Group title')
	 * @I18N
	 * @SafeValidator
	 * @var string
	 */
	public $title = '';

	/**
	 * @Label('Group description')
	 * @I18N
	 * @SafeValidator
	 * @var string
	 */
	public $description = '';

	/**
	 * Whenever group has text enabled
	 * @SafeValidator
	 * @var bool
	 */
	public $hasText = false;

	/**
	 * Assets list
	 * @DbRefArray(PageAsset, updatable = true)
	 * @SafeValidator
	 * @var PageAsset[]
	 */
	public $items = [];

	/**
	 * @Label('Assets count')
	 * @var int
	 */
	public $assetsCount = 0;

}
