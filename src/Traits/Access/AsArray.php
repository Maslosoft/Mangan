<?php

/**
 * This software package is licensed under AGPL or Commercial license.
 *
 * @package maslosoft/mangan
 * @licence AGPL or Commercial
 * @copyright Copyright (c) Piotr Masełkowski <pmaselkowski@gmail.com>
 * @copyright Copyright (c) Maslosoft
 * @copyright Copyright (c) Others as mentioned in code
 * @link https://maslosoft.com/mangan/
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

	public function offsetExists(mixed $offset): bool
	{
		// Allow only hash access
		if (!is_numeric($offset))
		{
			return (bool) $this->meta->$offset;
		}
		return false;
	}

	public function offsetGet(mixed $offset): mixed
	{
		return $this->$offset;
	}

	public function offsetSet(mixed $offset, mixed $value): void
	{
		$this->$offset = $value;
	}

	public function offsetUnset(mixed $offset): void
	{
		$this->$offset = $this->meta->$offset->default;
	}

}
