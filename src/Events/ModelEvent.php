<?php

/**
 * This software package is licensed under AGPL or Commercial license.
 *
 * @package maslosoft/mangan
 * @licence AGPL or Commercial
 * @copyright Copyright (c) Piotr Masełkowski <pmaselkowski@gmail.com>
 * @copyright Copyright (c) Maslosoft
 * @copyright Copyright (c) Others as mentioned in code
 * @link https://maslosoft.com/mangan/
 */

namespace Maslosoft\Mangan\Events;

use Maslosoft\Addendum\Interfaces\AnnotatedInterface;

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
	 * @var AnnotatedInterface
	 */
	public $sender = null;

	/**
	 * Current target for Event
	 * @var AnnotatedInterface
	 */
	public $currentTarget = null;

	/**
	 * Whenever event is handled
	 * @var bool
	 */
	public $handled = false;

	/**
	 * Event params
	 * @var mixed[]|null
	 */
	public $params = [];

	/**
	 * Event data
	 * @var mixed
	 */
	public $data = [];

	/**
	 * Name of class to which event was attached
	 * @var string
	 */
	public $source = '';

	/**
	 * Whenever to propagate event
	 * @var bool
	 */
	private $_propagate = true;

	/**
	 * Event constructor
	 *
	 * NOTE: Ensure that `handled` and `isValid` is properly set on event handler.
	 *
	 * @param AnnotatedInterface $sender
	 * @param mixed[] $params
	 */
	public function __construct(AnnotatedInterface $sender = null, $params = null)
	{
		$this->sender = $sender;
		$this->params = $params;
	}

	/**
	 * Stop event propagation
	 */
	public function stopPropagation()
	{
		$this->_propagate = false;
	}

	/**
	 * Whenever to propagate event
	 * @return bool
	 */
	public function propagate()
	{
		return $this->_propagate;
	}

}
