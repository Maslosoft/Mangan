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

namespace Maslosoft\Mangan\Annotations\Validators;

use Maslosoft\Addendum\Helpers\ParamsExpander;
use Maslosoft\Mangan\Meta\ValidatorMeta;

/**
 * NOTE: This class is automatically generated from Yii validator class.
 * This is not actual validator. For validator class @see CUrlValidator.
 */

/**
 * CUrlValidator validates that the attribute value is a valid http or https URL.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @version $Id$
 * @package system.validators
 * @since 1.0
 */
class UrlValidatorAnnotation extends ValidatorAnnotation
{

	use \Maslosoft\Mangan\Validators\Traits\AllowEmpty;

	public function init()
	{
		$this->_entity->validators[] = new ValidatorMeta(ParamsExpander::expand($this, [
					'allowEmpty',
					'message',
					'skipOnError',
					'on',
					'safe',
					'enableClientValidation',
					'except',
					'proxy'
		]));
	}

}
