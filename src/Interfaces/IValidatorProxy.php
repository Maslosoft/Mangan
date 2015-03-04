<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Maslosoft\Mangan\Interfaces;

/**
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
interface IValidatorProxy
{
	/**
	 * Set validator
	 * @param IValidator $validator
	 */
	public function setValidator(IValidator $validator);

	/**
	 * Get validator
	 * @return IValidator Validator instance
	 */
	public function getValidator();
}
