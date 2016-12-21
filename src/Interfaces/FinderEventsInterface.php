<?php

namespace Maslosoft\Mangan\Interfaces;

/**
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
interface FinderEventsInterface
{

	public function afterCount($model);

	public function afterExists($model);

	public function afterFind($model);

	/**
	 * Trigger before count event
	 * @return boolean
	 */
	public function beforeCount($model);

	/**
	 * Trigger before exists event
	 * @return boolean
	 */
	public function beforeExists($model);

	/**
	 * Trigger before find event
	 * @return boolean
	 */
	public function beforeFind($model);
}
