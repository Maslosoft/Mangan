<?php

namespace Maslosoft\Mangan\File\Sender;

use Maslosoft\Mangan\Interfaces\File\SenderInterface;
use Maslosoft\Mangan\Interfaces\File\WrapperInterface;

class Streamer extends Base implements SenderInterface
{
	public const BufferSize = 8192;
	public function send(WrapperInterface $wrapper): never
	{
		if (ob_get_length())
		{
			ob_end_clean();
		}
		$this->withHeaders($wrapper);
		$stream = $wrapper->getStream();

		while (!feof($stream))
		{
			echo fread($stream, self::BufferSize);
			if (ob_get_length())
			{
				ob_flush();
			}
			flush();
		}
		exit;
	}
}