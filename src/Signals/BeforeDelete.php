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

use Maslosoft\Addendum\Interfaces\IAnnotated;
use Maslosoft\Signals\ISignal;

/**
 * BeforeDelete
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class BeforeDelete implements ISignal
{

	/**
	 * Deleted document
	 * @var IAnnotated
	 */
	public $model = null;

	/**
	 * Constructor
	 * @param IAnnotated $model
	 */
	public function __construct(IAnnotated $model)
	{
		$this->model = $model;
	}

}
