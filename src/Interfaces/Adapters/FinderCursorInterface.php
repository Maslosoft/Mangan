<?php

namespace Maslosoft\Mangan\Interfaces\Adapters;

use Countable;
use Iterator;
use Maslosoft\Mangan\Interfaces\Criteria\LimitableInterface;

/**
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
interface FinderCursorInterface extends Iterator, Countable, LimitableInterface
{

	public function limit();
}
