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

use Maslosoft\Mangan\Decorators\EmbedRefArrayDecorator;
use Maslosoft\Mangan\Decorators\RelatedArrayDecorator;

/**
 * RelatedAnnotation
 * Shorthand notation:
 *
 * RelatedArray(Company\Project\Projects, join = {'_id' = 'entity_id'}, sort = {'_id' = 1}, true)
 *
 * Expanded notation:
 *
 * RelatedArray(class = Company\Project\Projects, join = {'_id' => 'entity_id'}, sort = {'_id' = 1}, updatable = true)
 *
 * @Conflicts('Embedded')
 * @Conflicts('EmbeddedArray')
 * @Conflicts('DbRef')
 * @Conflicts('DbRefArray')
 * @Conflicts('Related')
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class RelatedArrayAnnotation extends RelatedAnnotation
{

	public $class;
	public $join;
	public $updatable;
	public $value;

	public function init()
	{
		$relMeta = $this->_getMeta();
		$relMeta->single = false;
		$relMeta->isArray = true;
		$this->getEntity()->related = $relMeta;
		$this->getEntity()->propagateEvents = true;
		$this->getEntity()->owned = true;
		$this->getEntity()->decorators[] = RelatedArrayDecorator::class;
		$this->getEntity()->decorators[] = EmbedRefArrayDecorator::class;
	}

}
