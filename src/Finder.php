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

namespace Maslosoft\Mangan;

use Maslosoft\Addendum\Interfaces\AnnotatedInterface;
use Maslosoft\Mangan\Abstracts\AbstractFinder;
use Maslosoft\Mangan\Adapters\Finder\MongoAdapter;
use Maslosoft\Mangan\Exceptions\ManganException;
use Maslosoft\Mangan\Helpers\FinderEvents;
use Maslosoft\Mangan\Interfaces\EntityManagerInterface;
use Maslosoft\Mangan\Interfaces\FinderInterface;
use Maslosoft\Mangan\Meta\ManganMeta;
use Maslosoft\Mangan\Traits\Finder\FinderHelpers;
use Maslosoft\Mangan\Transformers\RawArray;

/**
 * Basic Finder implementation
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class Finder extends AbstractFinder implements FinderInterface
{

	use FinderHelpers;

	/**
	 * Constructor
	 *
	 * @param object $model Model instance
	 * @param EntityManagerInterface $em
	 * @param Mangan $mangan
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

	/**
	 * Create model related finder.
	 * This will create customized finder if defined in model with Finder annotation.
	 * If no custom finder is defined this will return default Finder.
	 *
	 * @param AnnotatedInterface $model
	 * @param EntityManagerInterface $em
	 * @param Mangan $mangan
	 * @return FinderInterface
	 */
	public static function create(AnnotatedInterface $model, $em = null, Mangan $mangan = null)
	{
		$finderClass = ManganMeta::create($model)->type()->finder ?: static::class;
		return new $finderClass($model, $em, $mangan);
	}

	protected function createModel($data)
	{
		if (!empty($data['$err']))
		{
			throw new ManganException(sprintf("There is an error in query: %s", $data['$err']));
		}
		return RawArray::toModel($data, $this->getModel());
	}

}
