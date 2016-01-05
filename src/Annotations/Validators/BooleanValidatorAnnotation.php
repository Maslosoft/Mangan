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
use Maslosoft\Mangan\Annotations\Validators\ValidatorAnnotation;
use Maslosoft\Mangan\Meta\ValidatorMeta;
use Maslosoft\Mangan\Validators\Proxy\BooleanProxy;

/**
 * CBooleanValidator validates that the attribute value is either {@link trueValue}  or {@link falseValue}.
 *
 * When using the {@link message} property to define a custom error message, the message
 * may contain additional placeholders that will be replaced with the actual content. In addition
 * to the "{attribute}" placeholder, recognized by all validators (see {@link CValidator}),
 * CBooleanValidator allows for the following placeholders to be specified:
 * <ul>
 * <li>{true}: replaced with value representing the true status {@link trueValue}.</li>
 * <li>{false}: replaced with value representing the false status {@link falseValue}.</li>
 * </ul>
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @version $Id$
 * @package system.validators
 */
class BooleanValidatorAnnotation extends ValidatorAnnotation
{

	use \Maslosoft\Mangan\Validators\Traits\Strict,
	  \Maslosoft\Mangan\Validators\Traits\AllowEmpty;

	/**
	 * @var mixed the value representing true status. Defaults to '1'.
	 */
	public $trueValue = '1';

	/**
	 * @var mixed the value representing false status. Defaults to '0'.
	 */
	public $falseValue = '0';

	public function init()
	{
		$this->proxy = BooleanProxy::class;
		$this->_entity->validators[] = new ValidatorMeta(ParamsExpander::expand($this, [
					'trueValue',
					'falseValue',
					'strict',
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
