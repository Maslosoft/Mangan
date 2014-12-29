<?php

/**
 * @licence For licence @see LICENCE.html
 * 
 * @copyright Maslosoft
 * @link http://maslosoft.com/
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
