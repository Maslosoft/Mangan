<?php

namespace Maslosoft\ManganTest\Models;

/**
 * CountableStub
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class CountableStub implements \Countable
{

	private $value = 10;

	public function __construct($value = 10)
	{
		$this->value = $value;
	}

	public function count()
	{
		return $this->value;
	}

}
