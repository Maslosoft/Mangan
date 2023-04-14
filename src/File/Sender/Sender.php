<?php

namespace Maslosoft\Mangan\File\Sender;

use Maslosoft\Mangan\Interfaces\File\SenderInterface;
use Maslosoft\Mangan\Interfaces\File\WrapperInterface;

class Sender extends Base implements SenderInterface
{
	public function send(WrapperInterface $wrapper): never
	{
		$this->withHeaders($wrapper);
		echo $wrapper->getBytes();
		exit;
	}

}