<?php

/**
 * This software package is licensed under AGPL or Commercial license.
 *
 * @package maslosoft/mangan
 * @licence AGPL or Commercial
 * @copyright Copyright (c) Piotr Masełkowski <pmaselkowski@gmail.com>
 * @copyright Copyright (c) Maslosoft
 * @copyright Copyright (c) Others as mentioned in code
 * @link http://maslosoft.com/mangan/
 */

namespace Maslosoft\Mangan\Validators\BuiltIn;

use Maslosoft\Addendum\Interfaces\AnnotatedInterface;
use Maslosoft\Mangan\Interfaces\Validators\ValidatorInterface;
use Maslosoft\Mangan\Meta\ManganMeta;

/**
 * UrlValidator
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class UrlValidator implements ValidatorInterface
{

	use \Maslosoft\Mangan\Validators\Traits\AllowEmpty,
	  \Maslosoft\Mangan\Validators\Traits\Messages,
	  \Maslosoft\Mangan\Validators\Traits\OnScenario,
	  \Maslosoft\Mangan\Validators\Traits\Safe;

	/**
	 * @Label('{attribute} must be valid url')
	 * @var string
	 */
	public $msgNotUrl = '';

	public function isValid(AnnotatedInterface $model, $attribute)
	{
		$valid = filter_var($model->$attribute, FILTER_VALIDATE_URL);
		if (!$valid)
		{
			$label = ManganMeta::create($model)->field($attribute)->label;
			$this->addError('msgNotUrl', ['{attribute}' => $label]);
			return false;
		}
		return true;
	}

}
