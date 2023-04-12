<?php

namespace Maslosoft\Mangan\Helpers\Cursor;

use MongoDB\Driver\Cursor;

function first(Cursor $cursor): null|array
{
	foreach($cursor as $item)
	{
		return (array)$item;
	}
	return null;
}