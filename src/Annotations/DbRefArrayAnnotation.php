<?php

/**
 * This software package is licensed under AGPL or Commercial license.
 *
 * @package maslosoft/mangan
 * @licence AGPL or Commercial
 * @copyright Copyright (c) Piotr Masełkowski <pmaselkowski@gmail.com>
 * @copyright Copyright (c) Maslosoft
 * @copyright Copyright (c) Others as mentioned in code
 * @link https://maslosoft.com/mangan/
 */

namespace Maslosoft\Mangan\Annotations;

use Maslosoft\Mangan\Decorators\DbRefArrayDecorator;
use Maslosoft\Mangan\Decorators\EmbedRefArrayDecorator;

/**
 * DB reference array annotation is used to create reference to other
 * or same collection items.
 *
 * All parameters are optional. However it is recommended to set
 * first parameter - default class for referenced objects.
 *
 * It takes first parameter as class name or class literal, and
 * second parameter to indicate if it should be updated along with
 * parent model.
 *
 * Most simple example - will allow many of any object types to be referenced:
 * ```
 * @DbRefArray
 * ```
 *
 * Example of updatable DB reference:
 * ```
 * @DbRefArray(ClassLiteral, true)
 * ```
 *
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
