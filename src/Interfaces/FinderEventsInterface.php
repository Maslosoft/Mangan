<?php

namespace Maslosoft\Mangan\Interfaces;

use Maslosoft\Addendum\Interfaces\AnnotatedInterface;

/**
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
interface FinderEventsInterface
{

	public function afterCount(FinderInterface $finder);

	public function afterExists(FinderInterface $finder);

	/**
	 * NOTE: This method always accepts as a second parameter
	 * currently found model, while finder might contain base or empty model
	 * @param AnnotatedInterface $model
	 */
	public function afterFind(FinderInterface $finder, AnnotatedInterface $model);

	/**
	 * Trigger before count event
	 * @return boolean
	 */
	public function beforeCount(FinderInterface $finder);

	/**
	 * Trigger before exists event
	 * @return boolean
	 */
	public function beforeExists(FinderInterface $finder);

	/**
	 * Trigger before find event
	 * @return boolean
	 */
	public function beforeFind(FinderInterface $finder);
}
