<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Maslosoft\Mangan\Transformers\Filters;

use Maslosoft\Mangan\EntityManager;
use Maslosoft\Mangan\Meta\DocumentPropertyMeta;

/**
 * This filter is intended to mark attribute as eligible for mass assignment via EntityManager::setAttributes()
 * @see EntityManager::setAttributes()
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class SafeFilter implements ITransformatorFilter
{

	public function fromModel($model, DocumentPropertyMeta $fieldMeta)
	{
		return $fieldMeta->safe;
	}

	public function toModel($model, DocumentPropertyMeta $fieldMeta)
	{
		return $fieldMeta->safe;
	}

}
