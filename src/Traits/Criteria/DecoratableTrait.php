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

namespace Maslosoft\Mangan\Traits\Criteria;

use Maslosoft\Addendum\Interfaces\AnnotatedInterface;
use Maslosoft\Mangan\Criteria\ConditionDecorator;
use Maslosoft\Mangan\Interfaces\ConditionDecoratorInterface;
use Maslosoft\Mangan\Interfaces\Criteria\DecoratableInterface;
use Maslosoft\Mangan\Interfaces\CriteriaInterface;
use Maslosoft\Mangan\Interfaces\Decorators\ConditionDecoratorTypeAwareInterface;
use Maslosoft\Mangan\Interfaces\Decorators\ConditionDecoratorTypeInterface;

/**
 * DecoratableTrait
 * @see DecoratableInterface
 * @see CriteriaInterface
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
trait DecoratableTrait
{

	/**
	 * Condition decorator instance
	 * @var ConditionDecoratorInterface
	 */
	private $cd = null;

	/**
	 * Get condition interface
	 * @return ConditionDecoratorInterface
	 */
	public function getCd()
	{
		if($this instanceof ConditionDecoratorTypeAwareInterface && $this->cd instanceof ConditionDecoratorTypeInterface)
		{
			$this->cd->setDecoratorType($this->getDecoratorType());
		}
		return $this->cd;
	}

	/**
	 * Set condition decorator interface
	 * @param ConditionDecoratorInterface $cd
	 * @return CriteriaInterface
	 */
	public function setCd(ConditionDecoratorInterface $cd)
	{
		if($this instanceof ConditionDecoratorTypeAwareInterface && $cd instanceof ConditionDecoratorTypeInterface)
		{
			$cd->setDecoratorType($this->getDecoratorType());
		}
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
		if($this instanceof ConditionDecoratorTypeAwareInterface && $this->cd instanceof ConditionDecoratorTypeInterface)
		{
			$this->cd->setDecoratorType($this->getDecoratorType());
		}
		return $this;
	}

}
