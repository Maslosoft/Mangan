<?php

namespace Maslosoft\Mangan\Interfaces\File;

interface SenderInterface
{
	public function send(WrapperInterface $wrapper): never;
}