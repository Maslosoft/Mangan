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

namespace Maslosoft\Mangan\Traits\Criteria;

use Maslosoft\Addendum\Interfaces\AnnotatedInterface;
use Maslosoft\Mangan\Criteria\ConditionDecorator;
use Maslosoft\Mangan\Interfaces\ConditionDecoratorInterface;
use Maslosoft\Mangan\Interfaces\Criteria\DecoratableInterface;
use Maslosoft\Mangan\Interfaces\CriteriaInterface;

/**
 * DecoratableTrait
 * @see DecoratableInterface
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
trait DecoratableTrait
{

	/**
	 *
	 * @var type
	 */
	private $cd = null;

	/**
	 * Get condition interface
	 * @return ConditionDecoratorInterface
	 */
	public function getCd()
	{
		return $this->cd;
	}

	/**
	 * Set condition decorator interface
	 * @param ConditionDecoratorInterface $cd
	 * @return DecoratableTrait
	 */
	public function setCd(ConditionDecoratorInterface $cd)
	{
		$this->cd = $cd;
		return $this;
	}

	/**
	 * Decorate and sanitize criteria with provided model.
	 * @param AnnotatedInterface $model Model to use for decorators and sanitizer when creating conditions. If null no decorators will be used. If model is provided it's sanitizers and decorators will be used.
	 * @param ConditionDecoratorInterface $decorator
	 * @return CriteriaInterface
	 */
	public function decorateWith($model, ConditionDecoratorInterface $decorator = null)
	{
		if (null !== $decorator)
		{
			$this->cd = $decorator;
		}
		else
		{
			$this->cd = new ConditionDecorator($model);
		}
		return $this;
	}

}
