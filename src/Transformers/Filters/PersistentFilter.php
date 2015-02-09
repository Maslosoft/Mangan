<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
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
