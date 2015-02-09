<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
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

	protected function _getTransformer($transformatorClass, DocumentTypeMeta $documentMeta, DocumentPropertyMeta $fieldMeta)
	{
		return Factory::create($transformatorClass, $documentMeta, $fieldMeta);
	}

}
