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

namespace Maslosoft\Mangan\Interfaces;

use Maslosoft\Mangan\Pagination;

/**
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
interface DataProviderInterface extends CriteriaAwareInterface, ModelAwareInterface, SortAwareInterface
{

	/**
	 * Returns the data items currently available, ensures that result is at leas empty array
	 * @param boolean $refresh whether the data should be re-fetched from persistent storage.
	 * @return array the list of data items currently available in this data provider.
	 */
	public function getData($refresh = false);

	/**
	 * Returns the number of data items in the current page.
	 * This is equivalent to <code>count($provider->getData())</code>.
	 * When {@link pagination} is set false, this returns the same value as {@link totalItemCount}.
	 * @param boolean $refresh whether the number of data items should be re-calculated.
	 * @return integer the number of data items in the current page.
	 */
	public function getItemCount($refresh = false);

	/**
	 * Returns the total number of data items.
	 * When {@link pagination} is set false, this returns the same value as {@link itemCount}.
	 * @return integer total number of possible data items.
	 */
	public function getTotalItemCount();

	/**
	 * Returns the pagination object.
	 * @param string $className the pagination object class name, use this param to override default pagination class.
	 * @return PaginationInterface|Pagination|false the pagination object. If this is false, it means the pagination is disabled.
	 */
	public function getPagination($className = Pagination::class);

	/**
	 * Set pagination. This method accepts following types:
	 *
	 * * boolean - set to false to disable pagination completely
	 * * string - pagination class name - will use default values
	 * * array - EmbeDi compatible pagination configuration
	 * * PaginationIntreface - Pagination interface compatible object instances
	 *
	 * @param bool|string|array|PaginationInterface|Pagination $pagination
	 */
	public function setPagination($pagination);
}
