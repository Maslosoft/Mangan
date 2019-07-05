<?php


namespace Maslosoft\Mangan\Scopes;


use function get_class;
use Maslosoft\Mangan\Criteria;
use Maslosoft\Mangan\Interfaces\ModelAwareInterface;
use Maslosoft\Mangan\Interfaces\ScopeInterface;
use Maslosoft\Mangan\Traits\ModelAwareTrait;

class SameClass implements ScopeInterface, ModelAwareInterface
{
	use ModelAwareTrait;

	public function getCriteria()
	{
		$criteria = new Criteria;
		$criteria->addCond('_class', '==', get_class($this->getModel()));
		return $criteria;
	}

}