<?php

/**
 * This software package is licensed under AGPL or Commercial license.
 *
 * @package maslosoft/mangan
 * @licence AGPL or Commercial
 * @copyright Copyright (c) Piotr Masełkowski <pmaselkowski@gmail.com>
 * @copyright Copyright (c) Maslosoft
 * @copyright Copyright (c) Others as mentioned in code
 * @link http://maslosoft.com/mangan/
 */

namespace Maslosoft\Mangan\Annotations;

use Maslosoft\Mangan\Decorators\DbRefArrayDecorator;
use Maslosoft\Mangan\Decorators\EmbedRefArrayDecorator;

/**
 * DB reference array annotation
 * @template DbRefArray(${class}, ${updatable})
 *
 * @Conflicts('Embedded')
 * @Conflicts('EmbeddedArray')
 * @Conflicts('DbRef')
 * @Conflicts('Related')
 * @Conflicts('RelatedArray')
 *
 * @Target('property')
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class DbRefArrayAnnotation extends DbRefAnnotation
{

	public $value = [];

	/**
	 * Comparing key. This is used to update db ref instances from external sources.
	 * @var string|array
	 */
	public $key = null;

	public function init()
	{
		$refMeta = $this->getDbRefMeta();
		$refMeta->key = $this->key;
		$refMeta->single = false;
		$refMeta->isArray = true;
		$this->getEntity()->dbRef = $refMeta;
		$this->getEntity()->propagateEvents = true;
		$this->getEntity()->owned = true;
		$this->getEntity()->decorators[] = DbRefArrayDecorator::class;
		$this->getEntity()->decorators[] = EmbedRefArrayDecorator::class;
		// Do not call parent init
	}

}
