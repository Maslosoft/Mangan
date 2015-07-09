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

namespace Maslosoft\Mangan\Meta;

/**
 * I18NMeta
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class I18NMeta
{

	use \Maslosoft\Addendum\Traits\MetaState;

	public $enabled = false;
	public $allowDefault = false;
	public $allowAny = false;

}
