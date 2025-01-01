<?php

/**
 * This software package is licensed under AGPL or Commercial license.
 *
 * @package   maslosoft/mangan
 * @licence   AGPL or Commercial
 * @copyright Copyright (c) Piotr Masełkowski <pmaselkowski@gmail.com>
 * @copyright Copyright (c) Maslosoft
 * @copyright Copyright (c) Others as mentioned in code
 * @link      https://maslosoft.com/mangan/
 */

namespace Maslosoft\Mangan;

use Maslosoft\Addendum\Interfaces\AnnotatedInterface;
use Maslosoft\Mangan\Abstracts\AbstractFinder;
use Maslosoft\Mangan\Adapters\Finder\MongoAdapter;
use Maslosoft\Mangan\Helpers\FinderEvents;
use Maslosoft\Mangan\Helpers\PkManager;
use Maslosoft\Mangan\Interfaces\Adapters\FinderCursorInterface;
use Maslosoft\Mangan\Interfaces\CriteriaInterface;
use Maslosoft\Mangan\Interfaces\EntityManagerInterface;
use Maslosoft\Mangan\Interfaces\FinderInterface;
use Maslosoft\Mangan\Meta\ManganMeta;
use Maslosoft\Mangan\Traits\Finder\CreateModel;
use Maslosoft\Mangan\Traits\Finder\FinderHelpers;
use MongoDB\Driver\Cursor;
use UnexpectedValueException;

/**
 * Basic Finder implementation
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class Finder extends AbstractFinder
{

	use CreateModel,
		FinderHelpers;

	/**
	 * Constructor
	 *
	 * @param object                 $model Model instance
	 * @param EntityManagerInterface $em
	 * @param Mangan                 $mangan
	 */
	public function __construct($model, $em = null, $mangan = null)
	{
		if (null === $mangan)
		{
			$mangan = Mangan::fromModel($model);
		}
		$this->setModel($model);
		$this->setScopeManager(new ScopeManager($model));
		$this->setAdapter(new MongoAdapter($model, $mangan, $em));

		$this->setProfiler($mangan->getProfiler());
		$this->setFinderEvents(new FinderEvents);
		$this->withCursor($mangan->useCursor);
	}

	public function findAll($criteria = null)
	{
		if ($this->getFinderEvents()->beforeFind($this))
		{
			$criteria = $this->getScopeManager()->apply($criteria);

			$options = [];

			if ($criteria->getSort() !== null)
			{
				$options['sort'] = $criteria->getSort();
			}
			if ($criteria->getLimit() !== null)
			{
				$options['limit'] = $criteria->getLimit();
			}
			if ($criteria->getOffset() !== null)
			{
				$options['skip'] = $criteria->getOffset();
			}
			if ($criteria->getSelect())
			{
				$options['projection'] = array_merge($criteria->getSelect(), ['_class' => true]);
			}

			$cursor = $this->getAdapter()->findMany($criteria, [], $options);

			assert(is_object($cursor), sprintf(
				'Expected cursor to be compatible object, got %s',
				gettype($cursor)));
			assert($cursor instanceof FinderCursorInterface || $cursor instanceof Cursor,
				new UnexpectedValueException(sprintf(
					'Expected `%s` or `%s` got `%s`',
					FinderCursorInterface::class,
					Cursor::class,
					get_class($cursor))));

			$this->getProfiler()->cursor($cursor);
			return $this->populateRecords($cursor);
		}
		return [];
	}

	public function exists(?CriteriaInterface $criteria = null)
	{
		if ($this->getFinderEvents()->beforeExists($this))
		{
			$criteria = $this->getScopeManager()->apply($criteria);

			//Select only Pk Fields to not fetch possibly large document
			$pkKeys = PkManager::getPkKeys($this->getModel());
			if (is_string($pkKeys))
			{
				$pkKeys = [$pkKeys];
			}
			$cursor = $this->getAdapter()->findMany($criteria, $pkKeys, ['limit' => 1]);

			// TODO Clumsy crap... Maybe better option, not using count?
			$exists = false;
			foreach($cursor as $item)
			{
				$exists = true;
			}
			$this->getFinderEvents()->afterExists($this);
			return $exists;
		}
		return false;
	}

	/**
	 * Create model related finder.
	 * This will create customized finder if defined in model with Finder annotation.
	 * If no custom finder is defined this will return default Finder.
	 *
	 * @param AnnotatedInterface $model
	 * @param null               $em
	 * @param Mangan|null        $mangan
	 * @return FinderInterface
	 */
	public static function create(AnnotatedInterface $model, $em = null, ?Mangan $mangan = null)
	{
		$finderClass = ManganMeta::create($model)->type()->finder ?: static::class;
		return new $finderClass($model, $em, $mangan);
	}

}
