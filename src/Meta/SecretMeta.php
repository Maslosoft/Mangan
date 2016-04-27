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
 * SecretMeta
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class SecretMeta extends BaseMeta
{

	/**
	 * Whether field is secret
	 * @var bool
	 */
	public $secret = true;

	/**
	 * Callback to process field if not empty
	 * @var callable|null
	 */
	public $callback = null;

}
