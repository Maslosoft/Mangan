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

use InvalidArgumentException;
use Maslosoft\Mangan\Meta\ManganMeta;
use Maslosoft\Mangan\Sanitizers\StringSanitizer;

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
	 *
	 * **NOTE**: This message will be used instead of any other validtor messages when set.
	 *
	 * @Sanitizer(StringSanitizer)
	 * @see StringSanitizer
	 * @var string
	 */
	public $message = '';

	/**
	 * Error messages
	 * @var string[]
	 */
	private $messages = [];

	/**
	 * Add error. Let it be either error message, or validator field with message.
	 *
	 * This is meant to be used inside validators.
	 *
	 * Will display value of `$msgError` if set or it's Label annotation value:
	 * ```php
	 * $this->addError('msgError');
	 * ```
	 *
	 * Will display error message as is:
	 * ```php
	 * $this->addError('This field is required');
	 * ```
	 *
	 * **NOTE**: If field `message` is set, it will use this custom message.
	 *
	 * @param string $message
	 * @param string[] $params
	 */
	public function addError($message, $params = [])
	{
		// Use custom message
		if (!empty($this->message))
		{
			$message = $this->message;
		}
		elseif (preg_match('~^[a-zA-Z0-9_]+$~', $message) && isset($this->$message))
		{
			// Use message from @Label if it's validator field and is empty
			if ($this->$message === '')
			{
				$fieldMeta = ManganMeta::create($this)->field($message);
				if (false === $fieldMeta)
				{
					throw new InvalidArgumentException(sprintf("Unknown validator message field: `%s`", $message));
				}
				$message = $fieldMeta->label;
			}
			else
			{
				$message = $this->$message;
			}
		}
		if (!empty($params))
		{
			$message = str_replace(array_keys($params), $params, $message);
		}
		$this->messages[] = $message;
	}

	/**
	 * Get error messages for this validator.
	 *
	 * This will return array of error messages if any. Order of messages is not guaranteed.
	 *
	 * @return string[]
	 */
	public function getErrors()
	{
		return $this->messages;
	}

}
