<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Maslosoft\Mangan\Meta;

/**
 * BaseMeta
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
abstract class BaseMeta
{

	public function __construct($data)
	{
		if(is_array($data))
		{
			foreach($data as $key => $value)
			{
				$this->$key = $value;
			}
		}
	}

}
