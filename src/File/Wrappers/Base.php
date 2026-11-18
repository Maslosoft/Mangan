<?php

namespace Maslosoft\Mangan\File\Wrappers;

abstract class Base
{
	abstract public function getStream();

	public function write(string $filename): bool
	{
		$stream = $this->getStream();
		try
		{
			$file = fopen($filename, 'wb');
			if ($file === false)
			{
				return false;
			}
			try
			{
				return stream_copy_to_stream($stream, $file) !== false && fflush($file);
			}
			finally
			{
				fclose($file);
			}
		}
		finally
		{
			fclose($stream);
		}
	}
}
