<?php

/**
 * This software package is licensed under New BSD license.
 *
 * @package maslosoft/mangan
 * @licence New BSD
 * @copyright Copyright (c) Piotr Masełkowski <pmaselkowski@gmail.com>
 * @copyright Copyright (c) Maslosoft
 * @copyright Copyright (c) Others as mentioned in code
 * @link http://maslosoft.com/mangan/
 */

namespace Maslosoft\Mangan;

use Maslosoft\Addendum\Interfaces\IAnnotated;
use Maslosoft\Mangan\Interfaces\IModel;
use Maslosoft\Mangan\Interfaces\IValidatable;

/**
 * Validator
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class Validator implements IValidatable
{

	const EventBeforeValidate = 'beforeValidate';
	const EventAfterValidate = 'afterValidate';

	/**
	 * Model instance
	 * @var IAnnotated
	 */
	private $_model = null;

	public function __construct(IAnnotated $model)
	{
		$this->_model = $model;
	}

	public function validate()
	{
		return true;
	}

	public function getErrors()
	{
		return [];
	}

}
