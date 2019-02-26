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

		// Attach pagination options if it's array.
		// It might be array, when configured via constructor
		if (is_array($this->pagination))
		{
			if (empty($this->pagination['class']))
			{
				$this->pagination['class'] = $className;
			}
			if(isset($this->pagination['pageSize']))
			{
				$this->pagination['size'] = $this->pagination['pageSize'];
				unset($this->pagination['pageSize']);
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
			if(isset($pagination['pageSize']))
			{
				$pagination['size'] = $pagination['pageSize'];
				unset($pagination['pageSize']);
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
