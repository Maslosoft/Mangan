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
use Maslosoft\Mangan\Validators\Proxy\CountProxy;
use Maslosoft\Mangan\Validators\Traits\AllowEmpty;
use Maslosoft\Mangan\Validators\Traits\When;

/**
 * CountValidator validates that the attribute array elements count is of certain length.
 *
 * Note, this validator should only be used with array type attributes or
 * `Countable` interface instance object.
 *
 * In addition to the {@link message} property for setting a custom error message,
 * CountValidator has a couple custom error messages you can set that correspond to different
 * validation scenarios. For defining a custom message when the string is too short,
 * you may use the {@link tooShort} property. Similarly with {@link tooLong}. The messages may contain
 * placeholders that will be replaced with the actual content. In addition to the "{attribute}"
 * placeholder, recognized by all validators (see {@link Validator}), StringValidator allows for the following
 * placeholders to be specified:
 * <ul>
 * <li>{min}: when using {@link tooShort}, replaced with minimum length, {@link min}, if set.</li>
 * <li>{max}: when using {@link tooLong}, replaced with the maximum length, {@link max}, if set.</li>
 * <li>{length}: when using {@link message}, replaced with the exact required length, {@link is}, if set.</li>
 * </ul>
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @version $Id$
 * @package system.validators
 * @since 1.0
 */
class CountValidatorAnnotation extends ValidatorAnnotation
{

	use AllowEmpty,
	  When;

	/**
	 * @var integer maximum length. Defaults to null, meaning no maximum limit.
	 */
	public $max = null;

	/**
	 * @var integer minimum length. Defaults to null, meaning no minimum limit.
	 */
	public $min = null;

	/**
	 * @var integer exact length. Defaults to null, meaning no exact length limit.
	 */
	public $is = null;

	/**
	 * @var string user-defined error message used when the value is too short.
	 */
	public $tooShort = null;

	/**
	 * @var string user-defined error message used when the value is too long.
	 */
	public $tooLong = null;

	/**
	 * @var string
	 */
	public $msgInvalid = '';

	/**
	 * @var string
	 */
	public $msgTooShort = '';

	/**
	 * @var string
	 */
	public $msgTooLong = '';

	/**
	 * @var string
	 */
	public $msgLength = '';

	public function init()
	{
		$this->proxy = CountProxy::class;
		$this->getEntity()->validators[] = new ValidatorMeta(ParamsExpander::expand($this, [
					'max',
					'min',
					'is',
					'when',
					'tooShort',
					'tooLong',
					'msgInvalid',
					'msgTooShort',
					'msgTooLong',
					'msgLength',
					'allowEmpty',
					'message',
					'skipOnError',
					'on',
					'safe',
					'except',
					'proxy'
		]));
	}

}
