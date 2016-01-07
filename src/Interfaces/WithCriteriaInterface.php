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

namespace Maslosoft\Mangan\Interfaces;

/**
 * Use this interface to associate Criteria with model
 * @see CriteriaInterface
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
interface WithCriteriaInterface
{

	/**
	 * Returns the mongo criteria associated with this model.
	 * @param boolean $createIfNull whether to create a criteria instance if it does not exist. Defaults to true.
	 * @return CriteriaInterface the query criteria that is associated with this model.
	 * This criteria is mainly used by {@link scopes named scope} feature to accumulate
	 * different criteria specifications.
	 * @since v1.0
	 */
	public function getDbCriteria($createIfNull = true);

	/**
	 * Set current object, this will override previous criteria
	 *
	 * @param CriteriaInterface|array $criteria
	 * @since v1.0
	 */
	public function setDbCriteria($criteria);
}
