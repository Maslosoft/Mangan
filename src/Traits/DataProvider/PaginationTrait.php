<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Maslosoft\Mangan\Traits\DataProvider;

use Maslosoft\EmbeDi\EmbeDi;
use Maslosoft\Mangan\Interfaces\PaginationInterface;
use Maslosoft\Mangan\Pagination;
use UnexpectedValueException;

/**
 * PaginationTrait
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
trait PaginationTrait
{

	/**
	 * Pagination instance
	 * @var boolean|array|PaginationInterface
	 */
	private $pagination = null;

	/**
	 * Returns the pagination object.
	 * @param string $className the pagination object class name, use this param to override default pagination class.
	 * @return PaginationInterface|Pagination|false the pagination object. If this is false, it means the pagination is disabled.
	 */
	public function getPagination($className = Pagination::class)
	{
		if ($this->pagination === false)
		{
			return false;
		}
		if ($this->pagination === null)
		{
			$this->pagination = new $className;
		}

		// FIXME: Attach pagination options if it's array.
		// It might be array, when configured via constructor
		if (is_array($this->pagination))
		{
			if (empty($this->pagination['class']))
			{
				$this->pagination['class'] = $className;
			}
			$this->pagination = EmbeDi::fly()->apply($this->pagination);
		}
		return $this->pagination;
	}

	/**
	 * Set pagination
	 * @param boolean|array|PaginationInterface $pagination
	 * @return static
	 */
	public function setPagination($pagination)
	{
		// Disable pagination completely
		if (false === $pagination)
		{
			$this->pagination = false;
			return $this;
		}

		// Configure from array
		if (is_array($pagination))
		{
			if (empty($pagination['class']))
			{
				$pagination['class'] = Pagination::class;
			}
			$this->pagination = EmbeDi::fly()->apply($pagination);
			return $this;
		}

		// Set object instance
		if ($pagination instanceof PaginationInterface)
		{
			$this->pagination = $pagination;
			return $this;
		}

		throw new UnexpectedValueException(sprintf('Expected `false` or `array` or `%s`, got %s', PaginationInterface::class, is_object($pagination) ? get_class($pagination) : gettype($pagination)));
	}

}
