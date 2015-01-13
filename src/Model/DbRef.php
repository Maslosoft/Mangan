<?php

/**
 * This software package is licensed under New BSD license.
 *
 * @package maslosoft/mangan
 * @licence New BSD
 * @copyright Copyright (c) Piotr Masełkowski <pmaselkowski@gmail.com>
 * @copyright Copyright (c) Maslosoft
 * @copyright Copyright (c) Others as mentioned in code
 * @link http://maslosoft.com/mangan/
 */

namespace Maslosoft\Mangan\Model;

use Maslosoft\Addendum\Interfaces\IAnnotated;

/**
 * DbRef
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class DbRef implements IAnnotated
{

	/**
	 * Referenced object class name
	 * @var string
	 */
	public $class = '';

	/**
	 * Primary key to retrieve object
	 * @var ObjectId|mixed|mixed[]
	 */
	public $pk = null;

}
