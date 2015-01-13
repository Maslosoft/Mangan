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

namespace Maslosoft\Mangan\Events;

/**
 * ModelEvent
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class ModelEvent
{

	public $isValid = false;
	public $sender;
	public $handled = false;
	public $params;

	public function __construct($sender = null, $params = null)
	{
		$this->sender = $sender;
		$this->params = $params;
	}

}
