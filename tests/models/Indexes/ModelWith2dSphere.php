<?php
/**
 * Created by PhpStorm.
 * User: peter
 * Date: 17.12.17
 * Time: 18:39
 */

namespace Maslosoft\ManganTest\Models\Indexes;


use Maslosoft\Mangan\Document;
use Maslosoft\Mangan\Helpers\IndexManager;
use Maslosoft\Mangan\Model\Geo;

class ModelWith2dSphere extends Document
{
	/**
	 * @Index(IndexManager::IndexType2dSphere)
	 *
	 * @Embedded(Geo)
	 *
	 * @see Geo
	 * @see IndexManager
	 * @var string
	 */
	public $loc = null;
}