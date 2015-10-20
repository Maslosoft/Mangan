<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Maslosoft\Mangan\Validators\BuiltIn;

use Maslosoft\Addendum\Interfaces\AnnotatedInterface;
use Maslosoft\Mangan\Interfaces\Validators\ValidatorInterface;

/**
 * UrlValidator
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class UrlValidator implements ValidatorInterface
{

	use \Maslosoft\Mangan\Validators\Traits\AllowEmpty,
	  \Maslosoft\Mangan\Validators\Traits\Messages;

	public function isValid(AnnotatedInterface $model, $attribute)
	{
		$valid = filter_var($model->$attribute, FILTER_VALIDATE_URL);
		if (!$valid)
		{
			$label = \Maslosoft\Mangan\Meta\ManganMeta::create($model)->field($attribute)->label;
			$this->addError('{attribute} must be valid url', ['{attribute}' => $label]);
			return false;
		}
		return true;
	}

}
