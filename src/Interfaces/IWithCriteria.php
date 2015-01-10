<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Maslosoft\Mangan\Interfaces;

use Maslosoft\Mangan\Criteria;

/**
 * Use this interface to associate Criteria with model
 * @see Criteria
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
interface IWithCriteria
{

	/**
	 * Returns the mongo criteria associated with this model.
	 * @param boolean $createIfNull whether to create a criteria instance if it does not exist. Defaults to true.
	 * @return Criteria the query criteria that is associated with this model.
	 * This criteria is mainly used by {@link scopes named scope} feature to accumulate
	 * different criteria specifications.
	 * @since v1.0
	 */
	public function getDbCriteria($createIfNull = true);

	/**
	 * Set girrent object, this will override previous criteria
	 *
	 * @param Criteria|array $criteria
	 * @since v1.0
	 */
	public function setDbCriteria($criteria);
}
