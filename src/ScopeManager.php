<?php

/**
 * This software package is licensed under New BSD license.
 *
 * @package maslosoft/mangan
 * @licence New BSD
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
	private $model = null;

	/**
	 *
	 * @var Criteria
	 */
	private $criteria = null;

	public function __construct($model)
	{
		$this->model = $model;
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
		if(is_array($criteria))
		{
			$criteria = new Criteria($criteria);
		}
		$criteria->mergeWith($this->criteria);
		return $criteria;
	}

	public function reset()
	{
		$this->_criteria = new Criteria();
		return $this;
	}

}
