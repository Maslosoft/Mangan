<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Maslosoft\Mangan\Transformers\Filters;

use Maslosoft\Mangan\Interfaces\Filters\Property\TransformatorFilterInterface;
use Maslosoft\Mangan\Meta\DocumentPropertyMeta;

/**
 * Secret filter. This is meant to be used only on raw array.
 * This will ignore empty values when saving document.
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class SecretFilter implements TransformatorFilterInterface
{

	public function fromModel($model, DocumentPropertyMeta $fieldMeta)
	{
		if ($fieldMeta->secret !== false)
		{
			return !empty($model->{$fieldMeta->name});
		}
		return true;
	}

	public function toModel($model, DocumentPropertyMeta $fieldMeta)
	{
		return true;
	}

}
