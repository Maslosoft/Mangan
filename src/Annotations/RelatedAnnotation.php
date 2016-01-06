<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Maslosoft\Mangan\Annotations;

use Maslosoft\Addendum\Helpers\ParamsExpander;
use Maslosoft\Mangan\Decorators\EmbedRefDecorator;
use Maslosoft\Mangan\Decorators\RelatedDecorator;
use Maslosoft\Mangan\Interfaces\SortInterface;
use Maslosoft\Mangan\Meta\ManganPropertyAnnotation;
use Maslosoft\Mangan\Meta\RelatedMeta;

/**
 * RelatedAnnotation
 * Shorthand notation:
 *
 * Related(Company\Project\Projects, join = {'_id' = 'entity_id'}, sort = {'_id' = 1}, true)
 *
 * Expanded notation:
 *
 * Related(class = Company\Project\Projects, join = {'_id' => 'entity_id'}, sort = {'_id' = 1}, updatable = true)
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class RelatedAnnotation extends ManganPropertyAnnotation
{

	public $class;
	public $join;
	public $updatable;
	public $value;

	public function init()
	{
		$relMeta = $this->_getMeta();
		$relMeta->single = true;
		$relMeta->isArray = false;
		$this->_entity->related = $relMeta;
		$this->_entity->propagateEvents = true;
		$this->_entity->owned = true;
		$this->_entity->decorators[] = RelatedDecorator::class;
		$this->_entity->decorators[] = EmbedRefDecorator::class;
	}

	/**
	 *
	 * @return RelatedMeta
	 */
	protected function _getMeta()
	{
		$data = ParamsExpander::expand($this, ['class', 'join', 'sort', 'updatable']);
		$relMeta = new RelatedMeta($data);
		if (!$relMeta->class)
		{
			$relMeta->class = $this->_meta->type()->name;
		}
		if (empty($relMeta->sort))
		{
			$relMeta->sort = [
				'_id' => SortInterface::SortAsc
			];
		}
		return $relMeta;
	}

}
