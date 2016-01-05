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
 * EmailValidator
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class EmailValidator implements ValidatorInterface
{

	use \Maslosoft\Mangan\Validators\Traits\AllowEmpty,
	  \Maslosoft\Mangan\Validators\Traits\Messages,
	  \Maslosoft\Mangan\Validators\Traits\OnScenario,
	  \Maslosoft\Mangan\Validators\Traits\Safe;

	/**
	 * @var string the regular expression used to validate the attribute value.
	 * @see http://www.regular-expressions.info/email.html
	 */
	public $pattern = '/^[a-zA-Z0-9!#$%&\'*+\\/=?^_`{|}~-]+(?:\.[a-zA-Z0-9!#$%&\'*+\\/=?^_`{|}~-]+)*@(?:[a-zA-Z0-9](?:[a-zA-Z0-9-]*[a-zA-Z0-9])?\.)+[a-zA-Z0-9](?:[a-zA-Z0-9-]*[a-zA-Z0-9])?$/';

	/**
	 * @var boolean whether to check the MX record for the email address.
	 * Defaults to false. To enable it, you need to make sure the PHP function 'checkdnsrr'
	 * exists in your PHP installation.
	 * Please note that this check may fail due to temporary problems even if email is deliverable.
	 */
	public $checkMX = false;

	/**
	 * @var boolean whether to check port 25 for the email address.
	 * Defaults to false. To enable it, ensure that the PHP functions 'dns_get_record' and
	 * 'fsockopen' are available in your PHP installation.
	 * Please note that this check may fail due to temporary problems even if email is deliverable.
	 */
	public $checkPort = false;

	/**
	 * @Label('{attribute} must be valid email address')
	 * @var string
	 */
	public $msgValid = '';

	/**
	 * @Label('Email domain "{domain}" does not exists')
	 * @var string
	 */
	public $msgDomain = '';

	/**
	 * @Label('Email service does not seem to be running at "{domain}"')
	 * @var string
	 */
	public $msgPort = '';

	public function isValid(AnnotatedInterface $model, $attribute)
	{
		if ($this->allowEmpty && empty($model->$attribute))
		{
			return true;
		}
		$label = ManganMeta::create($model)->field($attribute)->label;

		if (!is_scalar($model->$attribute))
		{
			$this->addError('msgValid', ['{attribute}' => $label]);
			return false;
		}
		$valid = filter_var($model->$attribute, FILTER_VALIDATE_EMAIL);
		if (!$valid)
		{
			$this->addError('msgValid', ['{attribute}' => $label]);
			return false;
		}
		if (!preg_match($this->pattern, $model->$attribute))
		{
			$this->addError('msgValid', ['{attribute}' => $label]);
			return false;
		}
		$domain = rtrim(substr($model->$attribute, strpos($model->$attribute, '@') + 1), '>');
		if ($this->checkMX)
		{
			if (function_exists('checkdnsrr'))
			{
				if (!checkdnsrr($domain, 'MX'))
				{
					$this->addError('msgDomain', ['{domain}' => $domain]);
					return false;
				}
			}
		}
		if ($this->checkPort)
		{
			if ($this->checkMxPorts($domain))
			{
				$this->addError('msgPort', ['{domain}' => $domain]);
				return false;
			}
		}
		return true;
	}

	/**
	 * Retrieves the list of MX records for $domain and checks if port 25
	 * is opened on any of these.
	 * @param string $domain domain to be checked
	 * @return boolean true if a reachable MX server has been found
	 */
	protected function checkMxPorts($domain)
	{
		$records = dns_get_record($domain, DNS_MX);
		if ($records === false || empty($records))
		{
			return false;
		}
		$sort = function ($a, $b)
		{
			return $a['pri'] - $b['pri'];
		};
		usort($records, $sort);
		foreach ($records as $record)
		{
			$errno = null;
			$errstr = null;
			$handle = @fsockopen($record['target'], 25, $errno, $errstr, 3);
			if ($handle !== false)
			{
				fclose($handle);
				return true;
			}
		}
		return false;
	}

}
