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

namespace Maslosoft\Mangan\Profillers;

use Maslosoft\Mangan\Interfaces\ProfilerInterface;
use MongoCursor;

/**
 * NullProfiller
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class NullProfiler implements ProfilerInterface
{

	public function profile($data)
	{
		// Do nothing
	}

	public function cursor($cursor)
	{
		// Do nothing
	}

}
