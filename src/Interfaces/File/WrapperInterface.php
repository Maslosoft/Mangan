<?php

namespace Maslosoft\Mangan\Interfaces\File;

interface WrapperInterface
{
	public function getBytes(): string;

	public function getMetadata();

	public function getLength(): int;

	public function getStream();

	/**
	 * Write file to filesystem
	 * @param string $filename
	 * @return bool
	 */
	public function write(string $filename): bool;
}