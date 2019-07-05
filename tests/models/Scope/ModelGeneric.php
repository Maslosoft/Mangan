<?php


namespace Maslosoft\ManganTest\Models\Scope;


use Maslosoft\Mangan\Document;
use Maslosoft\Mangan\Interfaces\CollectionNameInterface;

class ModelGeneric extends Document implements CollectionNameInterface
{
	public $title = '';

	public function getCollectionName()
	{
		return 'scopes';
	}
}