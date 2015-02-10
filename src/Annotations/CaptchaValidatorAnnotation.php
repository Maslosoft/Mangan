<?php

/**
 * This software package is licensed under New BSD license.
 *
 * @package maslosoft/mangan
 * @licence New BSD
 * @copyright Copyright (c) Piotr Masełkowski <pmaselkowski@gmail.com>
 * @copyright Copyright (c) Maslosoft
 * @copyright Copyright (c) Others as mentioned in code
 * @link http://maslosoft.com/mangan/
 */

namespace Maslosoft\Mangan\Annotations;

use Maslosoft\Mangan\Base\ValidatorAnnotation;
use Maslosoft\Mangan\Base\IBuiltInValidatorAnnotation;

/**
 * NOTE: This class is automatically generated from Yii validator class.
 * This is not actual validator. For validator class @see CCaptchaValidator.
 */

/**
 * CCaptchaValidator validates that the attribute value is the same as the verification code displayed in the CAPTCHA.
 *
 * CCaptchaValidator should be used together with {@link CCaptchaAction}.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @version $Id$
 * @package system.validators
 * @since 1.0
 */
class CaptchaValidatorAnnotation extends ValidatorAnnotation implements IBuiltInValidatorAnnotation
{

	/**
	 * @var boolean whether the comparison is case sensitive. Defaults to false.
	 */
	public $caseSensitive = false;

	/**
	 * @var string ID of the action that renders the CAPTCHA image. Defaults to 'captcha',
	 * meaning the 'captcha' action declared in the current controller.
	 * This can also be a route consisting of controller ID and action ID.
	 */
	public $captchaAction = 'captcha';

	/**
	 * @var boolean whether the attribute value can be null or empty.
	 * Defaults to false, meaning the attribute is invalid if it is empty.
	 */
	public $allowEmpty = false;

}
