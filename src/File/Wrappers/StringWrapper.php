<?php

namespace Maslosoft\Mangan\File\Wrappers;

use Maslosoft\Mangan\File\Wrappers\Traits\Data;
use Maslosoft\Mangan\Interfaces\File\WrapperInterface;

class StringWrapper extends Base implements WrapperInterface
{
	use Data;

	public function __construct(private readonly string $value, private readonly array $data)
	{

	}
	public function getStream()
	{
		$stream = fopen('php://memory', 'rb+');
		fwrite($stream, $this->value);
		rewind($stream);
		return $stream;
	}

	public function getBytes(): string
	{
		return $this->value;
	}

	public function getLength(): int
	{
		return strlen($this->value);
	}

}