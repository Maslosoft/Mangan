<?php

namespace Maslosoft\ManganTest\Models\ActiveDocument;

use Maslosoft\Mangan\Document;
use Maslosoft\Mangan\Sanitizers\MongoObjectId;
use MongoId;

/**
  int
  string
  bool
  float
  array
  null
 */

/**
 * Basic php types
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class DocumentBaseAttributes extends Document
{

	public $int = 23;
	public $string = 'test';
	public $bool = true;
	public $float = 0.23;
	public $array = [];
	public $null = null;

}
