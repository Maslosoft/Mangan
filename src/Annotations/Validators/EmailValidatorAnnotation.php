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
use Maslosoft\Mangan\Validators\BuiltIn\EmailValidator;
use Maslosoft\Mangan\Validators\Proxy\EmailProxy;
use Maslosoft\Mangan\Validators\Traits\AllowEmpty;

/**
 * EmailValidator validates that the attribute value is a valid email address.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @version $Id$
 * @package system.validators
 * @since 1.0
 */
class EmailValidatorAnnotation extends ValidatorAnnotation
{

	use AllowEmpty;

	/**
	 * @var string the regular expression used to validate the attribute value.
	 * @see http://www.regular-expressions.info/email.html
	 */
	public $pattern = EmailValidator::EmailPattern;

	/**
	 * @var string the regular expression used to validate email addresses with the name part.
	 * This property is used only when {@link allowName} is true.
	 * @see allowName
	 */
	public $fullPattern = EmailValidator::FullEmailPattern;

	/**
	 * @var boolean whether to allow name in the email address (e.g. "Qiang Xue <qiang.xue@gmail.com>"). Defaults to false.
	 * @see fullPattern
	 */
	public $allowName = false;

	/**
	 * @var boolean whether to check the MX record for the email address.
	 * Defaults to false. To enable it, you need to make sure the PHP function 'checkdnsrr'
	 * exists in your PHP installation.
	 */
	public $checkMX = false;

	/**
	 * @var boolean whether to check port 25 for the email address.
	 * Defaults to false. To enable it, ensure that the PHP functions 'dns_get_record' and
	 * 'fsockopen' are available in your PHP installation.
	 */
	public $checkPort = false;

	public function init()
	{
		$this->proxy = EmailProxy::class;
		$this->getEntity()->validators[] = new ValidatorMeta(ParamsExpander::expand($this, [
					'pattern',
					'fullPattern',
					'allowName',
					'checkMX',
					'checkPort',
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
