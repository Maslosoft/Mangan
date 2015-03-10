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

namespace Maslosoft\Mangan\Transformers\Filters;

use Maslosoft\Mangan\Meta\DocumentPropertyMeta;

/**
 * PersistentFilter
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class PersistentFilter implements ITransformatorFilter
{

	public function fromModel($model, DocumentPropertyMeta $fieldMeta)
	{
		return $fieldMeta->persistent;
	}

	public function toModel($model, DocumentPropertyMeta $fieldMeta)
	{
		return $fieldMeta->persistent;
	}

}
