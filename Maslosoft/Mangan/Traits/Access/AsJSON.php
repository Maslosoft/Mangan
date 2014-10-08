<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Maslosoft\Mangan\Traits\Access;

/**
 * AsJSON
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
trait AsJSON
{

	public function toJsonArray()
	{
		/**
		 * TODO Use toArray method
		 * TODO filter out fields with @Json(false)
		 */
		throw new Exception('Not implemented');
	}

	public function toJson()
	{
		return json_encode($this->toJsonArray());
	}

}
