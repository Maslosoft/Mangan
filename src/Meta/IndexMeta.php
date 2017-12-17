<?php
/**
 * Created by PhpStorm.
 * User: peter
 * Date: 17.12.17
 * Time: 15:27
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