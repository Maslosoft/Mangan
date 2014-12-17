<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Maslosoft\Mangan\Decorators;

use Maslosoft\Mangan\Interfaces\I18NAble;
use Maslosoft\Mangan\ManganException;

/**
 * This creates i18n fields
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class I18NDecorator implements IDecorator
{

	public function read($model, $name, $value)
	{
		if (!$model instanceof I18NAble)
		{
			throw new ManganException(sprintf('Model class %s must implement interface %s to support I18N fields', get_class($model), I18NAble::class));
		}
		return $value[$model->getLang()];
	}

	public function write($model, $name, $value)
	{
		if (!$model instanceof I18NAble)
		{
			throw new ManganException(sprintf('Model class %s must implement interface %s to support I18N fields', get_class($model), I18NAble::class));
		}
		return [$model->getLang() => $value];
	}

}
