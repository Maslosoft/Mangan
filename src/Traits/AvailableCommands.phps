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

namespace Maslosoft\Mangan\Traits;

/**
 * AvailableCommands
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
trait AvailableCommands
{
	abstract public function call($command, $arguments = []);
	
}