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

namespace Maslosoft\Mangan\Criteria;

use Maslosoft\Addendum\Interfaces\AnnotatedInterface;

/**
 * Conditions
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class Conditions
{

	const FieldName = 1;
	const Operator = 2;
	const Value = 3;

	public function __construct()
	{
		;
	}

	public function add()
	{

	}

	public function addOr()
	{
		return $this;
	}

	public function addAnd()
	{
		return $this;
	}

	public function fromArray($conditions)
	{

	}

	public function get()
	{

	}

}
