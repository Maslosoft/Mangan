<?php

namespace Maslosoft\ManganTest\Models;

use Maslosoft\Mangan\Document;

/**
 * ModelWithImmutableValidator
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class ModelWithImmutableValidator extends Document
{

	/**
	 * @ImmutableValidator
	 * @var string
	 */
	public $state = '';
	public $installed = false;

}
