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

namespace Maslosoft\Mangan\Interfaces\Filters\Property;

use Maslosoft\Mangan\Meta\DocumentPropertyMeta;

/**
 * ITransformatorFilter
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
interface TransformatorFilterInterface
{

	public function fromModel($model, DocumentPropertyMeta $fieldMeta);

	public function toModel($model, DocumentPropertyMeta $fieldMeta);
}
