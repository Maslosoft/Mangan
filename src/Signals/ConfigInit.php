<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
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

	private $config = [];
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
