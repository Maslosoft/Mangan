<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Maslosoft\ManganTest\Models;

/**
 * GetSetComponent
 * @property string $name property
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class GetSetComponent
{

	use \Maslosoft\Mangan\Traits\Access\GetSet;

	const NameValue = 'costa';

	private $_name = '';

	public function getName()
	{
		return $this->_name;
	}

	public function setName($name)
	{
		$this->_name = $name;
	}

}
