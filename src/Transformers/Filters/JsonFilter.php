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

namespace Maslosoft\Mangan\Transformers\Filters;

use Maslosoft\Mangan\Interfaces\Filters\Property\TransformatorFilterInterface;
use Maslosoft\Mangan\Meta\DocumentPropertyMeta;

/**
 * JsonFilter
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class JsonFilter implements TransformatorFilterInterface
{
	public function fromModel($model, DocumentPropertyMeta $fieldMeta)
	{
		return $fieldMeta->toJson;
	}

	public function toModel($model, DocumentPropertyMeta $fieldMeta)
	{
		return $fieldMeta->fromJson;
	}

}
