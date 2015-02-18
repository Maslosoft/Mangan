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

/**
 * NOTE: This class is automatically generated from Yii validator class.
 * This is not actual validator. For validator class @see CInlineValidator.
 */

/**
 * CInlineValidator represents a validator which is defined as a method in the object being validated.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @version $Id: CInlineValidator.php 3517 2011-12-28 23:22:21Z mdomba $
 * @package system.validators
 * @since 1.0
 */
class InlineValidatorAnnotation extends ValidatorAnnotation
{

	/**
	 * @var string the name of the validation method defined in the active record class
	 */
	public $method = NULL;

	/**
	 * @var array additional parameters that are passed to the validation method
	 */
	public $params = NULL;

	/**
	 * @var string the name of the method that returns the client validation code (See {@link clientValidateAttribute}).
	 */
	public $clientValidate = NULL;

}