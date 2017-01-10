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

namespace Maslosoft\Mangan\Profillers;

use Maslosoft\Mangan\Interfaces\ManganAwareInterface;
use Maslosoft\Mangan\Interfaces\ProfilerInterface;
use Maslosoft\Mangan\Mangan;
use MongoCursor;

/**
 * Profiller
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class LoggingProfiler implements ProfilerInterface, ManganAwareInterface
{

	/**
	 * Mangan instance
	 * @var Mangan
	 */
	private $mangan = null;

	/**
	 * Proifile any data
	 * @param string $data
	 */
	public function profile($data)
	{
		$this->mangan->getLogger()->info($data);
	}

	/**
	 * Profile cursor
	 * @param MongoCursor $cursor
	 */
	public function cursor($cursor)
	{
		$this->mangan->getLogger()->info(var_export($cursor->explain(), true));
	}

	/**
	 * Set mangan instance
	 * @param Mangan $mangan
	 * @return LoggingProfiler
	 */
	public function setMangan(Mangan $mangan)
	{
		$this->mangan = $mangan;
		return $this;
	}

}
