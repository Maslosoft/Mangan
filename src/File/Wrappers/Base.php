<?php

namespace Maslosoft\Mangan\File\Wrappers;

abstract class Base
{
	abstract public function getStream();

	public function write(string $filename): bool
	{
		$stream = $this->getStream();
		$file = fopen($filename, 'wb');
		while($buffer = fread($stream, 8192))
		{
			fwrite($file, $buffer);
		}

		return fclose($stream) && fclose($file);
	}
}