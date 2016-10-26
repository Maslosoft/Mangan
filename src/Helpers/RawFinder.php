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

namespace Maslosoft\Mangan\Helpers;

use Maslosoft\Mangan\Exceptions\ManganException;
use Maslosoft\Mangan\Finder;

/**
 * Finder variant which returns raw arrays.
 * For internal or special cases use.
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class RawFinder extends Finder
{

	public function __construct($model, $em = null)
	{
		parent::__construct($model, $em);
		// Cannot use cursors in raw finder, as it will clash with PkManager
		$this->withCursor(false);
	}

	protected function populateRecord($data)
	{
		if (!empty($data['$err']))
		{
			throw new ManganException(sprintf("There is an error in query: %s", $data['$err']));
		}
		return $data;
	}

}
