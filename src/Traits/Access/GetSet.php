<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Maslosoft\Mangan\Traits\Access;

/**
 * GetSet
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
trait GetSet
{

	public function __get($name)
	{
		if ($this->_hasGetter($name))
		{
			return $this->{$this->_nameGetter($name)}();
		}
	}

	public function __set($name, $value)
	{
		if ($this->_hasSetter($name))
		{
			$this->{$this->_nameSetter($name)}($value);
		}
	}

	protected function _hasGetter($name)
	{
		return method_exists($this, $this->_nameGetter($name));
	}

	protected function _hasSetter($name)
	{
		return method_exists($this, $this->_nameSetter($name));
	}

	private function _nameGetter($name)
	{
		return sprintf('get%s', ucfirst($name));
	}

	private function _nameSetter($name)
	{
		return sprintf('set%s', ucfirst($name));
	}

}
