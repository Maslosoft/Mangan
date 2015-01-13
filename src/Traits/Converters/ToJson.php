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

namespace Maslosoft\Mangan\Traits\Converters;

/**
 * AsJSON
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
trait ToJson
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
