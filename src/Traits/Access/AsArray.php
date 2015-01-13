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

namespace Maslosoft\Mangan\Traits\Access;

use ArrayAccess;
use Maslosoft\Addendum\Collections\Meta;

/**
 * This trait is intented to stub interface ArrayAccess
 * @see ArrayAccess
 * @property Meta $meta Metadata of document
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
trait AsArray
{

	public function offsetExists($offset)
	{
		// Allow only hash access
		if (!is_numeric($offset))
		{
			return (bool) $this->meta->$offset;
		}
		return false;
	}

	public function offsetGet($offset)
	{
		return $this->$offset;
	}

	public function offsetSet($offset, $value)
	{
		$this->$offset = $value;
	}

	public function offsetUnset($offset)
	{
		$this->$offset = $this->meta->$offset->default;
	}

}
