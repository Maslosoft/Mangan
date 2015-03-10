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
 * ITransformatorFilter
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
interface ITransformatorFilter
{
	public function fromModel($model, DocumentPropertyMeta $fieldMeta);

	public function toModel($model, DocumentPropertyMeta $fieldMeta);


}
