<?php

/**
 * This software package is licensed under AGPL or Commercial license.
 *
 * @package maslosoft/mangan
 * @licence AGPL or Commercial
 * @copyright Copyright (c) Piotr Masełkowski <pmaselkowski@gmail.com>
 * @copyright Copyright (c) Maslosoft
 * @copyright Copyright (c) Others as mentioned in code
 * @link http://maslosoft.com/mangan/
 */

namespace Maslosoft\Mangan\Annotations\Indexes;

use Exception;
use Maslosoft\Mangan\Meta\ManganPropertyAnnotation;

/**
 * IndexAnnotation
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class IndexAnnotation extends ManganPropertyAnnotation
{

	const Ns = __NAMESPACE__;

	public function init()
	{
		throw new Exception('Not implemented');
	}

}
