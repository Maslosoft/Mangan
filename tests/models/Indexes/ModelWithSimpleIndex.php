<?php

namespace Maslosoft\ManganTest\Models\Indexes;

use Maslosoft\Mangan\Document;

class ModelWithSimpleIndex extends Document
{
	/**
	 * @Index
	 * @var string
	 */
	public $title = '';
}