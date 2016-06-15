<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Maslosoft\Mangan\Signals;

use Maslosoft\Signals\Interfaces\SignalInterface;

/**
 * ConfigInit
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class ConfigInit implements SignalInterface
{

	private $config = [];

	public function __construct(&$config)
	{
		$this->config = &$config;
	}

	public function apply($configuration)
	{
		$this->config = array_replace_recursive($this->config, $configuration);
	}

}
