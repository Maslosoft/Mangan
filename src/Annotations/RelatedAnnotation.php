<?php

/**
 * This software package is licensed under AGPL or Commercial license.
 *
 * @package maslosoft/mangan
 * @licence AGPL or Commercial
 * @copyright Copyright (c) Piotr Masełkowski <pmaselkowski@gmail.com>
 * @copyright Copyright (c) Maslosoft
 * @copyright Copyright (c) Others as mentioned in code
 * @link http://maslosoft.com/mangan/
 */

namespace Maslosoft\Mangan\Annotations;

use Maslosoft\Addendum\Helpers\ParamsExpander;
use Maslosoft\Mangan\Decorators\EmbedRefDecorator;
use Maslosoft\Mangan\Decorators\RelatedDecorator;
use Maslosoft\Mangan\Interfaces\SortInterface;
use Maslosoft\Mangan\Meta\ManganPropertyAnnotation;
use Maslosoft\Mangan\Meta\RelatedMeta;
use UnexpectedValueException;

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
 *
 * @Conflicts('Embedded')
 * @Conflicts('EmbeddedArray')
 * @Conflicts('DbRef')
 * @Conflicts('DbRefArray')
 * @Conflicts('RelatedArray')
 *
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
		$this->getEntity()->related = $relMeta;
		$this->getEntity()->propagateEvents = true;
		$this->getEntity()->owned = true;
		$this->getEntity()->decorators[] = RelatedDecorator::class;
		$this->getEntity()->decorators[] = EmbedRefDecorator::class;
	}

	/**
	 *
	 * @return RelatedMeta
	 */
	protected function _getMeta()
	{
		$data = ParamsExpander::expand($this, ['class', 'join', 'sort', 'updatable']);
		if (empty($this->getEntity()->related))
		{
			$relMeta = new RelatedMeta();
		}
		else
		{
			$relMeta = $this->getEntity()->related;
		}
		foreach ($data as $key => $val)
		{
			$relMeta->$key = $val;
		}
		if (!$relMeta->class)
		{
			$relMeta->class = $this->getMeta()->type()->name;
		}
		if (empty($relMeta->join))
		{
			throw new UnexpectedValueException(sprintf('Parameter `join` is required for `%s`, model `%s`, field `%s`', static::class, $this->getMeta()->type()->name, $this->getEntity()->name));
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
