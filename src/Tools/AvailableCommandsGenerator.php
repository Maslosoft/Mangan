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

namespace Maslosoft\Mangan\Tools;

use Maslosoft\Mangan\Command;
use Maslosoft\MiniView\MiniView;
use ReflectionClass;

/**
 * AvailableCommandsGenerator
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class AvailableCommandsGenerator
{

	public function generate()
	{
		$destName = (new ReflectionClass(\Maslosoft\Mangan\Traits\AvailableCommands::class))->getFileName();
		$srcName = $destName . 's';

		$view = new MiniView($this);
		$cmd = new Command();
		$commands = $cmd->listCommands()['commands'];
		$functions = [];
		foreach ($commands as $name => $cmdData)
		{
			if ($cmdData['adminOnly'])
			{
				continue;
			}
			$functions[] = $view->render('command', [
				'mongoName' => $name,
				'name' => $this->_sanitize($name),
				'help' => explode("\n", $cmdData['help'])
					], true);
		}

		$src = file_get_contents($srcName);
		$code = implode("\n", $functions) . "\n}";
		$result = str_replace('}', $code, $src);

		file_put_contents($destName, $result);
	}

	private function _sanitize($name)
	{
		$renames = [
			'clone' => 'cloneDb',
			'eval' => 'evalJs',
			'mapreduce.shardedfinish' => 'mapReduceShardedFinish'
		];
		if (isset($renames[$name]))
		{
			return $renames[$name];
		}
		return $name;
	}

}
