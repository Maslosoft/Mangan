<?php

namespace Maslosoft\ManganTest\Models;

use Maslosoft\Mangan\Document;

/**
 * ModelWithImmutableAgainstValidator
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class ModelWithImmutableAgainstValidator extends Document
{

	/**
	 * @ImmutableValidator(against = 'installed')
	 * @var string
	 */
	public $state = '';
	public $installed = false;

}
