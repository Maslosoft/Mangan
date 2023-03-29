<?php

namespace Maslosoft\ManganTest\Models;

use Maslosoft\Mangan\Interfaces\ModelInterface;
use Maslosoft\Mangan\Sanitizers\MongoObjectId;
use MongoDB\BSON\ObjectId as MongoId;

/**
 * Basic php types
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class BaseAttributesAnnotations implements ModelInterface
{

	/**
	 * @Sanitizer(MongoObjectId)
	 * @see MongoObjectId
	 * @Label('Database id')
	 * @var MongoId
	 */
	public $_id = null;

	/**
	 * @Label('Integer field')
	 * @var int
	 */
	public $int = 23;

	/**
	 * @Label('String value')
	 * @var string
	 */
	public $string = 'test';

	/**
	 * @Label('Second string value')
	 * @var string
	 */
	public $stringSecond = 'test 2';

	/**
	 * @Label('Boolean value')
	 * @var bool
	 */
	public $bool = true;

	/**
	 * @Label('Float value')
	 * @var bool
	 */
	public $float = 0.23;

	/**
	 * @Label('Array value')
	 * @var []
	 */
	public $array = [];

	/**
	 * @Label('Null value')
	 * @var null
	 */
	public $null = null;

}
