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

use Maslosoft\Mangan\Exceptions\ManganException;
use Maslosoft\Mangan\Interfaces\Criteria\LimitableInterface;
use Maslosoft\Mangan\Interfaces\Criteria\MergeableInterface;
use Maslosoft\Mangan\Interfaces\CriteriaAwareInterface;
use Maslosoft\Mangan\Interfaces\DataProviderInterface;
use Maslosoft\Mangan\Interfaces\FinderInterface;
use Maslosoft\Mangan\Interfaces\WithCriteriaInterface;
use Maslosoft\Mangan\Traits\DataProvider\ConfigureTrait;
use Maslosoft\Mangan\Traits\DataProvider\CriteriaTrait;
use Maslosoft\Mangan\Traits\DataProvider\DataTrait;
use Maslosoft\Mangan\Traits\DataProvider\PaginationTrait;
use Maslosoft\Mangan\Traits\ModelAwareTrait;
use Maslosoft\Mangan\Traits\SortAwareTrait;

/**
 * Mongo document data provider
 *
 * Implements a data provider based on Document.
 *
 * DataProvider provides data in terms of Document objects which are
 * of class {@link modelClass}. It uses the AR {@link EntityManager::findAll} method
 * to retrieve the data from database. The {@link query} property can be used to
 * specify various query options, such as conditions, sorting, pagination, etc.
 *
 * @author Ianaré Sévi
 * @author Dariusz Górecki <darek.krk@gmail.com>
 * @author Invenzzia Group, open-source division of CleverIT company http://www.invenzzia.org
 * @copyright 2011 CleverIT http://www.cleverit.com.pl
 * @since v1.0
 */
class DataProvider implements DataProviderInterface
{

	use ConfigureTrait,
	  CriteriaTrait,
	  DataTrait,
	  ModelAwareTrait,
	  PaginationTrait,
	  SortAwareTrait;

	const CriteriaClass = Criteria::class;

	/**
	 * Finder instance
	 * @var FinderInterface
	 */
	private $finder = null;
	private $totalItemCount = null;

	/**
	 * Constructor.
	 * @param mixed $modelClass the model class (e.g. 'Post') or the model finder instance
	 * (e.g. <code>Post::model()</code>, <code>Post::model()->published()</code>).
	 * @param array $config configuration (name=>value) to be applied as the initial property values of this class.
	 * @since v1.0
	 */
	public function __construct($modelClass, $config = [])
	{
		$this->configure($modelClass, $config);
		$this->finder = Finder::create($this->getModel());
	}

	/**
	 * Returns the number of data items in the current page.
	 * This is equivalent to <code>count($provider->getData())</code>.
	 * When {@link pagination} is set false, this returns the same value as {@link totalItemCount}.
	 * @param boolean $refresh whether the number of data items should be re-calculated.
	 * @return integer the number of data items in the current page.
	 */
	public function getItemCount($refresh = false)
	{
		return count($this->getData($refresh));
	}

	/**
	 * Returns the total number of data items.
	 * When {@link pagination} is set false, this returns the same value as {@link itemCount}.
	 * @return integer total number of possible data items.
	 */
	public function getTotalItemCount()
	{
		if ($this->totalItemCount === null)
		{
			$this->totalItemCount = $this->finder->count($this->getCriteria());
		}
		return $this->totalItemCount;
	}

	/**
	 * Fetches the data from the persistent data storage.
	 * @return Document[]|Cursor list of data items
	 * @since v1.0
	 */
	protected function fetchData()
	{
		$criteria = $this->configureFetch();

		// Finally apply all to finder
		return $this->finder->findAll($criteria);
	}

}
