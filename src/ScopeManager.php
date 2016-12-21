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

use Maslosoft\Mangan\Abstracts\AbstractScopeManager;
use Maslosoft\Mangan\Interfaces\ScopeManagerInterface;

/**
 * ScopeManager
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class ScopeManager extends AbstractScopeManager implements ScopeManagerInterface
{

	public function __construct($model)
	{
		$this->setModel($model);
	}

	public function getNewCriteria($criteria = null)
	{
		$newCriteria = new Criteria($criteria);
		$newCriteria->decorateWith($this->getModel());
		return $newCriteria;
	}

}
