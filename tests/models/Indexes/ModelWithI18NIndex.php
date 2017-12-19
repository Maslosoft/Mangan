<?php

namespace Maslosoft\ManganTest\Models\Indexes;

use Maslosoft\Mangan\Document;
use Maslosoft\Mangan\Sort;

class ModelWithI18NIndex extends Document
{
	/**
	 * @Index(Sort::SortAsc)
	 * @Index(Sort::SortDesc)
	 * @I18N
	 *
	 * @see Sort
	 * @var string
	 */
	public $title = '';
}