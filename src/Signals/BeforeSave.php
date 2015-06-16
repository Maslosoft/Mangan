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

namespace Maslosoft\Mangan\Signals;

use Maslosoft\Addendum\Interfaces\AnnotatedInterface;
use Maslosoft\Signals\ISignal;

/**
 * BeforeSave
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class BeforeSave implements ISignal
{

	/**
	 * Saved document
	 * @var AnnotatedInterface
	 */
	public $model = null;

	/**
	 * Constructor
	 * @param AnnotatedInterface $model
	 */
	public function __construct(AnnotatedInterface $model)
	{
		$this->model = $model;
	}

}
