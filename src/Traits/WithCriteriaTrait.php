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

namespace Maslosoft\Mangan\Traits;

use Maslosoft\Mangan\Criteria;
use Maslosoft\Mangan\Interfaces\CriteriaInterface;
use Maslosoft\Mangan\Interfaces\WithCriteriaInterface;

/**
 * WithCriteriaTrait
 * @see WithCriteriaInterface
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
trait WithCriteriaTrait
{

	/**
	 * Criteria
	 * @var CriteriaInterface
	 */
	private $_criteria = null;

	/**
	 * Returns the mongo criteria associated with this model.
	 * @param boolean $createIfNull whether to create a criteria instance if it does not exist. Defaults to true.
	 * @return CriteriaInterface the query criteria that is associated with this model.
	 * This criteria is mainly used by {@link scopes named scope} feature to accumulate
	 * different criteria specifications.
	 * @since v1.0
	 * @Ignored
	 */
	public function getDbCriteria($createIfNull = true)
	{
		if ($this->_criteria === null)
		{
			if (($c = $this->defaultScope()) !== [] || $createIfNull)
			{
				$this->_criteria = new Criteria($c);
			}
		}
		return $this->_criteria;
	}

	/**
	 * Set girrent object, this will override previous criteria
	 *
	 * @param CriteriaInterface|array $criteria
	 * @since v1.0
	 * @Ignored
	 */
	public function setDbCriteria($criteria)
	{
		if (is_array($criteria))
		{
			$this->_criteria = new Criteria($criteria);
		}
		else if ($criteria instanceof CriteriaInterface)
		{
			$this->_criteria = $criteria;
		}
		else
		{
			$this->_criteria = new Criteria();
		}
	}

}
