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

use Maslosoft\Mangan\Mangan;
use Maslosoft\Signals\Interfaces\SignalInterface;

/**
 * ConfigInit
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class ConfigInit implements SignalInterface
{

	/**
	 * Entire configuration of mangan
	 * @var array
	 */
	private $config = [];

	/**
	 * Mangan instance/connection ID
	 * @var string
	 */
	private $connectionId = '';

	public function __construct(&$config, $connectionId = Mangan::DefaultConnectionId)
	{
		$this->config = &$config;
		$this->connectionId = $connectionId;
	}

	/**
	 * Get connection id for which current signal is emitted
	 * @return string
	 */
	public function getConnectionId()
	{
		return $this->connectionId;
	}

	/**
	 * Merge supplied configuration with Mangan configuration.
	 * @param array $configuration
	 * @return
	 */
	public function apply($configuration)
	{
		$this->config = array_replace_recursive($this->config, $configuration);
		return $this;
	}

}
