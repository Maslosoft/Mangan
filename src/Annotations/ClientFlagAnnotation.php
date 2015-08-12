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

namespace Maslosoft\Mangan\Annotations;

use Maslosoft\Mangan\Meta\ManganTypeAnnotation;

/**
 * ClientFlag
 * Set client flag
 * Example: ClientFlag(w = 0, fsync = false)
 * @see \Maslosoft\Mangan\Traits\Defaults\MongoClientOptions
 * @template ClientFlag(${flags})
 * @Target('class')
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class ClientFlagAnnotation extends ManganTypeAnnotation
{

	use \Maslosoft\Mangan\Traits\Defaults\MongoClientOptions;

	public $value = [];

	public function init()
	{
		foreach ($this->value as $name => $value)
		{
			$this->_entity->clientFlags[$name] = $value;
		}
	}

}
