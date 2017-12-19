<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Maslosoft\ManganTest\Models\Plain;

use Maslosoft\Addendum\Interfaces\AnnotatedInterface;

/**
 * SimplePlainEmbedded
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class SimplePlainEmbedded implements AnnotatedInterface
{

	const Name = 'foo';
	const Active = true;
	const Visits = 3;

	/**
	 * @Label('Simple name')
	 * @var string
	 */
	public $name = self::Name;

	/**
	 * @Label('Is active')
	 * @var bool
	 */
	public $active = self::Active;

	/**
	 * @Label('How many visits')
	 * @var int
	 */
	public $visits = self::Visits;

}
