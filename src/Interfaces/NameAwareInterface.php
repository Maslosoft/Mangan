<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Maslosoft\Mangan\Interfaces;

use Maslosoft\Mangan\Helpers\Transformator;

/**
 * Use this interface to provide name for underlying class.
 *
 * @see Transformator
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
interface NameAwareInterface
{

	public function getName();

	public function setName($value);
}
