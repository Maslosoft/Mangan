<?php

/**
 * This software package is licensed under AGPL or Commercial license.
 *
 * @package maslosoft/mangan
 * @licence AGPL or Commercial
 * @copyright Copyright (c) Piotr Masełkowski <pmaselkowski@gmail.com>
 * @copyright Copyright (c) Maslosoft
 * @copyright Copyright (c) Others as mentioned in code
 * @link https://maslosoft.com/mangan/
 */

namespace Maslosoft\Mangan\Traits;

use Maslosoft\Mangan\Criteria;
use Maslosoft\Mangan\Interfaces\CriteriaInterface;
use Maslosoft\Mangan\Interfaces\WithCriteriaInterface;

/**
 * Attach criteria to model
 *
 * @see WithCriteriaInterface
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
trait WithCriteriaTrait
{

	/**
	 * Criteria
	 * @var CriteriaInterface|Criteria
	 */
	private $_criteria = null;

	/**
	 * Returns the Criteria associated with this model.
	 * @param boolean $createIfNull whether to create a criteria instance if it does not exist. Defaults to true.
	 * @return CriteriaInterface|Criteria the query criteria that is associated with this model.
	 * @since v1.0
	 * @Ignored
	 */
	public function getDbCriteria($createIfNull = true)
	{
		if ($this->_criteria === null)
		{
			if ($createIfNull)
			{
				$this->_criteria = new Criteria;
			}
		}
		return $this->_criteria;
	}

	/**
	 * Set new criteria, previous criteria will be destroyed.
	 *
	 * @param CriteriaInterface|Criteria|array $criteria
	 * @return static
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
		return $this;
	}

}
