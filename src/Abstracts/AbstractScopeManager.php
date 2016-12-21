<?php

namespace Maslosoft\Mangan\Abstracts;

use Maslosoft\Mangan\Document;
use Maslosoft\Mangan\Interfaces\Criteria\DecoratableInterface;
use Maslosoft\Mangan\Interfaces\CriteriaAwareInterface;
use Maslosoft\Mangan\Interfaces\CriteriaInterface;
use Maslosoft\Mangan\Interfaces\ModelAwareInterface;
use Maslosoft\Mangan\Interfaces\ScopeManagerInterface;
use Maslosoft\Mangan\Interfaces\WithCriteriaInterface;
use Maslosoft\Mangan\Traits\ModelAwareTrait;

/**
 * Base class for implementing scope managers
 *
 * @see ScopeManagerInterface
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
abstract class AbstractScopeManager implements ModelAwareInterface
{

	use ModelAwareTrait;

	/**
	 *
	 * @var CriteriaInterface
	 */
	private $criteria = null;

	/**
	 * Returns the declaration of named scopes.
	 * A named scope represents a query criteria that can be chained together with
	 * other named scopes and applied to a query. This method should be overridden
	 * by child classes to declare named scopes for the particular document classes.
	 * For example, the following code declares two named scopes: 'recently' and
	 * 'published'.
	 * <pre>
	 * return array(
	 * 	'published'=>array(
	 * 		'conditions'=>array(
	 * 				'status'=>array('==', 1),
	 * 		),
	 * 	),
	 * 	'recently'=>array(
	 * 		'sort'=>array('create_time'=>Criteria::SortDesc),
	 * 		'limit'=>5,
	 * 	),
	 * );
	 * </pre>
	 * If the above scopes are declared in a 'Post' model, we can perform the following
	 * queries:
	 * <pre>
	 * $posts=Post::model()->published()->findAll();
	 * $posts=Post::model()->published()->recently()->findAll();
	 * $posts=Post::model()->published()->published()->recently()->find();
	 * </pre>
	 *
	 * @return array the scope definition. The array keys are scope names; the array
	 * values are the corresponding scope definitions. Each scope definition is represented
	 * as an array whose keys must be properties of {@link Criteria}.
	 * @since v1.0
	 */
	public function scopes()
	{
		return [];
	}

	/**
	 * Returns the default named scope that should be implicitly applied to all queries for this model.
	 * Note, default scope only applies to SELECT queries. It is ignored for INSERT, UPDATE and DELETE queries.
	 * The default implementation simply returns an empty array. You may override this method
	 * if the model needs to be queried with some default criteria (e.g. only active records should be returned).
	 * @return array the mongo criteria. This will be used as the parameter to the constructor
	 * of {@link Criteria}.
	 * @since v1.2.2
	 */
	public function defaultScope()
	{
		return [];
	}

	/**
	 * Resets all scopes and criteria applied including default scope.
	 *
	 * @return Document
	 * @since v1.0
	 */
	public function resetScope()
	{
		$this->criteria = $this->getNewCriteria();
		return $this;
	}

	/**
	 * Apply scopes to criteria, will create criteria object if not provided and pass it by reference
	 * @param CriteriaInterface|array|null $criteria
	 * @return CriteriaInterface
	 */
	public function apply(&$criteria = null)
	{
		if (null === $criteria)
		{
			return $this->getModelCriteria();
		}
		elseif (is_array($criteria))
		{
			$criteria = $this->getNewCriteria($criteria);
		}
		$criteria->mergeWith($this->criteria);
		$criteria->mergeWith($this->getModelCriteria());
		if ($criteria instanceof DecoratableInterface)
		{
			$criteria->decorateWith($this->getModel());
		}
		return $criteria;
	}

	public function reset()
	{
		$this->criteria = $this->getNewCriteria();
		return $this;
	}

	protected function getModelCriteria()
	{
		$criteria = null;
		if ($this->model instanceof WithCriteriaInterface)
		{
			$criteria = $this->model->getDbCriteria();
		}
		elseif ($this->model instanceof CriteriaAwareInterface)
		{
			$criteria = $this->model->getCriteria();
		}
		if (empty($criteria))
		{
			return $this->getNewCriteria();
		}
		return $criteria;
	}

	abstract public function getNewCriteria($criteria = null);
}
