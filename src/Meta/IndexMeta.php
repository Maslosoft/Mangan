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

namespace Maslosoft\Mangan\Meta;


use Maslosoft\Addendum\Traits\MetaState;

class IndexMeta
{
	use MetaState;

	public $keys = [];

	public $options = [];

	public function __construct($keys = null, $options = null)
	{
		$this->keys = $keys;
		$this->options = $options;
	}
}