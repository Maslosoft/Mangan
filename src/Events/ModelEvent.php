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

namespace Maslosoft\Mangan\Events;

use Maslosoft\Addendum\Interfaces\IAnnotated;

/**
 * ModelEvent
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class ModelEvent
{

	/**
	 * Event name
	 * @var string
	 */
	public $name = '';

	/**
	 * Whenever event is valid
	 * @var bool
	 */
	public $isValid = false;

	/**
	 * Event sender
	 * @var IAnnotated
	 */
	public $sender = null;

	/**
	 * Whenever event is handled
	 * @var bool
	 */
	public $handled = false;

	/**
	 * Event params
	 * @var mixed[]
	 */
	public $params = [];

	/**
	 * Event data
	 * @var mixed[]
	 */
	public $data = [];

	/**
	 * Event constructor
	 * @param IAnnotated $sender
	 * @param mixed[] $params
	 */
	public function __construct(IAnnotated $sender = null, $params = null)
	{
		$this->sender = $sender;
		$this->params = $params;
	}

}
