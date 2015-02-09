<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Maslosoft\Mangan\Transformers\Filters;

use Maslosoft\Mangan\Meta\DocumentPropertyMeta;

/**
 * DocumentArrayFilter
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class DocumentArrayFilter implements ITransformatorFilter
{

	public function fromModel($model, DocumentPropertyMeta $fieldMeta)
	{
		return $fieldMeta->toArray;
	}

	public function toModel($model, DocumentPropertyMeta $fieldMeta)
	{
		return $fieldMeta->fromArray;
	}

}
