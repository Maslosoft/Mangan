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
use MongoDB\Driver\CursorInterface;

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
	 * @param CursorInterface $cursor
	 */
	public function cursor($cursor)
	{
		$context = ['cursorType' => get_debug_type($cursor)];
		if ($cursor instanceof CursorInterface)
		{
			$server = $cursor->getServer();
			$context['cursorId'] = (string) $cursor->getId();
			$context['server'] = $server->getHost() . ':' . $server->getPort();
		}
		$this->mangan->getLogger()->info('MongoDB query cursor', $context);
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
