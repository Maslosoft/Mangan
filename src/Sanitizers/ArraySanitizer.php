<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Maslosoft\Mangan\Sanitizers;

/**
 * ArraySanitizer
 * NOTE: This should NOT be used directly.
 * Use &commat;SanitizerArray annotation instead.
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class ArraySanitizer implements ISanitizer
{

	/**
	 * Sanitizer instance
	 * @var ISanitizer
	 */
	private $_sanitizer = null;

	public function __construct(ISanitizer $sanitizer)
	{
		$this->_sanitizer = $sanitizer;
	}

	public function read($model, $dbValue)
	{
		$result = [];
		foreach ((array) $dbValue as $key => $value)
		{
			$result[$key] = $this->_sanitizer->read($model, $value);
		}
		return $result;
	}

	public function write($model, $phpValue)
	{
		$result = [];
		foreach ((array) $phpValue as $key => $value)
		{
			$result[$key] = $this->_sanitizer->write($model, $value);
		}
		return $result;
	}

}
