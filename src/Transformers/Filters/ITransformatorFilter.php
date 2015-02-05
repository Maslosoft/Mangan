<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Maslosoft\Transformers\Filters;

/**
 * ITransformatorFilter
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class ITransformatorFilter
{
	public function fromModel($model, $fieldMeta);

	public function toModel($model, $fieldMeta);


}
