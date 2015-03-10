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

namespace Maslosoft\Mangan\Helpers\PropertyFilter;

use Maslosoft\Mangan\Helpers\Transformator;
use Maslosoft\Mangan\Meta\DocumentPropertyMeta;
use Maslosoft\Mangan\Meta\DocumentTypeMeta;
use Maslosoft\Mangan\Transformers\Filters\ITransformatorFilter;

/**
 * Filter
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class Filter extends Transformator implements ITransformatorFilter
{

	public function fromModel($model, DocumentPropertyMeta $fieldMeta)
	{
		return $this->getFor($fieldMeta->name)->fromModel($model, $fieldMeta);
	}

	public function toModel($model, DocumentPropertyMeta $fieldMeta)
	{
		return $this->getFor($fieldMeta->name)->fromModel($model, $fieldMeta);
	}

		/**
	 * Get transformer
	 * @param string $transformatorClass
	 * @param DocumentTypeMeta $modelMeta
	 * @param DocumentPropertyMeta $fieldMeta
	 * @return ITransformatorFilter
	 */
	protected function _getTransformer($transformatorClass, DocumentTypeMeta $modelMeta, DocumentPropertyMeta $fieldMeta)
	{
		return Factory::create($transformatorClass, $modelMeta, $fieldMeta);
	}

}
