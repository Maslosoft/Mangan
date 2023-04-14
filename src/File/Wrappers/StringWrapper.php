<?php

namespace Maslosoft\Mangan\File\Wrappers;

use Maslosoft\Mangan\Interfaces\File\WrapperInterface;

class StringWrapper extends Base implements WrapperInterface
{
	public function __construct(private readonly string $value, private readonly array $data)
	{

	}
	public function getStream()
	{
		// TODO: Implement getStream() method.
	}

	public function getBytes(): string
	{
		// TODO: Implement getBytes() method.
	}

	public function getMetadata()
	{
		// TODO: Implement getMetadata() method.
	}

	public function getLength(): int
	{
		// TODO: Implement getLength() method.
	}

}