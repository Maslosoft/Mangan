<?php

/**
 * This software package is licensed under AGPL or Commercial license.
 *
 * @package maslosoft/mangan
 * @licence AGPL or Commercial
 * @copyright Copyright (c) Piotr Masełkowski <pmaselkowski@gmail.com>
 * @copyright Copyright (c) Maslosoft
 * @copyright Copyright (c) Others as mentioned in code
 * @link https://maslosoft.com/mangan/
 */

namespace Maslosoft\Mangan\Annotations\Validators;

use Maslosoft\Addendum\Helpers\ParamsExpander;
use Maslosoft\Mangan\Meta\ValidatorMeta;
use Maslosoft\Mangan\Validators\Proxy\NumberProxy;

/**
 * NumberValidator validates that the attribute value is a number.
 *
 * In addition to the {@link message} property for setting a custom error message,
 * NumberValidator has a couple custom error messages you can set that correspond to different
 * validation scenarios. To specify a custom message when the numeric value is too big,
 * you may use the {@link tooBig} property. Similarly with {@link tooSmall}.
 * The messages may contain additional placeholders that will be replaced
 * with the actual content. In addition to the "{attribute}" placeholder, recognized by all
 * validators (see {@link Validator}), NumberValidator allows for the following placeholders
 * to be specified:
 * <ul>
 * <li>{min}: when using {@link tooSmall}, replaced with the lower limit of the number {@link min}.</li>
 * <li>{max}: when using {@link tooBig}, replaced with the upper limit of the number {@link max}.</li>
 * </ul>
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @version $Id$
 * @package system.validators
 * @since 1.0
 */
class NumericalValidatorAnnotation extends ValidatorAnnotation
{

	use \Maslosoft\Mangan\Validators\Traits\AllowEmpty;

	/**
	 * @var boolean whether the attribute value can only be an integer. Defaults to false.
	 */
	public $integerOnly = false;

	/**
	 * @var integer|float upper limit of the number. Defaults to null, meaning no upper limit.
	 */
	public $max = NULL;

	/**
	 * @var integer|float lower limit of the number. Defaults to null, meaning no lower limit.
	 */
	public $min = NULL;

	/**
	 * @var string user-defined error message used when the value is too big.
	 */
	public $tooBig = NULL;

	/**
	 * @var string user-defined error message used when the value is too small.
	 */
	public $tooSmall = NULL;

	public function init()
	{
		$this->proxy = NumberProxy::class;
		$this->getEntity()->validators[] = new ValidatorMeta(ParamsExpander::expand($this, [
					'integerOnly',
					'allowEmpty',
					'max',
					'min',
					'tooBig',
					'tooSmall',
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
