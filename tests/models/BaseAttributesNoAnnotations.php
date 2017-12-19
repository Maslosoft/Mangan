<?php

namespace Maslosoft\ManganTest\Models;

use Maslosoft\Mangan\Interfaces\ModelInterface;

/**
 * Basic php types
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class BaseAttributesNoAnnotations implements ModelInterface
{

	public $_id = null;
	public $int = 23;
	public $string = 'test';
	public $bool = true;
	public $float = 0.23;
	public $array = [];
	public $null = null;

}
