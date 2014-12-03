<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Maslosoft\Mangan\Meta;

use Maslosoft\Addendum\Collections\Meta;
use Maslosoft\Addendum\Interfaces\IAnnotated;
use Maslosoft\Mangan\Options\ManganMetaOptions;
use Maslosoft\Addendum\Options\MetaOptions;

/**
 * Mangan metadata container class
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class ManganMeta extends Meta
{

	public static function create(IAnnotated $component, MetaOptions $options = null)
	{
		if (null === $options)
		{
			$options = new ManganMetaOptions();
		}
		return parent::create($component, $options);
	}

}
