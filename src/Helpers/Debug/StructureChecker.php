<?php


namespace Maslosoft\Mangan\Helpers\Debug;


use function array_walk_recursive;
use function get_class;
use Maslosoft\Addendum\Interfaces\AnnotatedInterface;
use Maslosoft\Mangan\Exceptions\StructureException;

class StructureChecker
{
	public function checkEmbeds($data)
	{
		array_walk_recursive($data, [$this, 'checkEmbed']);
		return true;
	}

	public function checkEmbed($data)
	{
		if ($data instanceof AnnotatedInterface)
		{
			$params = [
				get_class($data)
			];
			$message = vsprintf('To store embedded/ref/related model (%s) it must be marked with @Embedded/@Related/@DbRef (Array) annotation', $params);
			throw new StructureException($message);
		}
	}
}