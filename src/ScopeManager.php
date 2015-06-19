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

namespace Maslosoft\Mangan;

/**
 * ScopeManager
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class ScopeManager
{

	/**
	 * Model instance
	 * @var IModel
	 */
	private $_model = null;

	/**
	 *
	 * @var Criteria
	 */
	private $_criteria = null;

	public function __construct($model)
	{
		$this->_model = $model;
	}

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
	 * 		'sort'=>array('create_time'=>Criteria::SORT_DESC),
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
		$this->_criteria = new Criteria();
		return $this;
	}

	/**
	 * Apply scopes to criteria, will create criteria object if not provided and pass it by reference
	 * @param Criteria|array|null $criteria
	 * @return Criteria
	 */
	public function apply(&$criteria = null)
	{
		if (null === $criteria)
		{
			return new Criteria();
		}
		if (is_array($criteria))
		{
			$criteria = new Criteria($criteria);
		}
		$criteria->mergeWith($this->_criteria);
		return $criteria;
	}

	public function reset()
	{
		$this->_criteria = new Criteria();
		return $this;
	}

}
