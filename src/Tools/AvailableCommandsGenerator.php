<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Maslosoft\Mangan\Tools;

/**
 * AvailableCommandsGenerator
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class AvailableCommandsGenerator
{

	public function generate()
	{
		$cmd = new \Maslosoft\Mangan\Command();
		$commands = $cmd->listCommands();
		
	}

}
