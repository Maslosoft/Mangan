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

namespace Maslosoft\Mangan\Helpers;

use Maslosoft\Addendum\Interfaces\AnnotatedInterface;
use Maslosoft\Mangan\Exceptions\ManganException;
use Maslosoft\Mangan\Finder;
use Maslosoft\Mangan\Interfaces\EntityManagerInterface;
use Maslosoft\Mangan\Interfaces\FinderInterface;
use Maslosoft\Mangan\Mangan;

/**
 * Finder variant which returns raw arrays.
 * For internal or special cases use.
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class RawFinder extends Finder
{

	public function __construct($model, $em = null, $mangan = null)
	{
		parent::__construct($model, $em, $mangan);
		// Cannot use cursors in raw finder, as it will clash with PkManager
		$this->withCursor(false);
	}

	/**
	 * Create raw finder instance.
	 *
	 * @param AnnotatedInterface $model
	 * @param EntityManagerInterface $em
	 * @param Mangan $mangan
	 * @return FinderInterface
	 */
	public static function create(AnnotatedInterface $model, $em = null, Mangan $mangan = null)
	{
		return new static($model, $em, $mangan);
	}

	protected function populateRecord($data)
	{
		return $this->createModel($data);
	}

	protected function createModel($data)
	{
		if (!empty($data['$err']))
		{
			throw new ManganException(sprintf("There is an error in query: %s", $data['$err']));
		}
		return $data;
	}

}
