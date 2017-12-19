<?php

namespace Maslosoft\ManganTest\Models;

use Maslosoft\Mangan\EmbeddedDocument;
use Maslosoft\Mangan\Traits\Model\WithParentTrait;

/**
 * ModelWithParentId
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class ModelWithParentId extends EmbeddedDocument
{

	use WithParentTrait;
}
