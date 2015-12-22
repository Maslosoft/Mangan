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

namespace Maslosoft\Mangan\Validators\Traits;

/**
 * Basic implementation of messaging for validators
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
trait Messages
{

	/**
	 * Custom error message. May contain special placeholders, ie: {attribute}, {value}
	 * which will be replaced with attribute label or value, depending on validator.
	 * @var string
	 */
	public $message = null;
	private $messages = [];

	public function addError($message, $params = [])
	{
		if (!empty($this->message))
		{
			$message = $this->message;
		}
		if (!empty($params))
		{
			$message = str_replace(array_keys($params), $params, $message);
		}
		$this->messages[] = $message;
	}

	public function getErrors()
	{
		return $this->messages;
	}

}
