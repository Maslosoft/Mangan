<?php

namespace Maslosoft\ManganTest\Models;

use Maslosoft\Mangan\Document;

/**
 * ModelWithEmbeddedModelWithParentId
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class ModelWithEmbeddedModelWithParentId extends Document
{

	/**
	 * @Embedded(ModelWithParentId)
	 * @var ModelWithParentId
	 */
	public $sub = null;

}
