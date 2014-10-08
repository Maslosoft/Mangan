<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Maslosoft\Mangan\Traits\Access;

use ArrayAccess;

/**
 * This trait is intented to stub interface ArrayAccess
 * @see ArrayAccess
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
