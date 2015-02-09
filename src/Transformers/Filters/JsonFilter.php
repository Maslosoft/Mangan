<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Maslosoft\Mangan\Transformers\Filters;

use Maslosoft\Mangan\Meta\DocumentPropertyMeta;

/**
 * JsonFilter
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class JsonFilter implements ITransformatorFilter
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
