<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
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

	public function addError($message, ... $params)
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
