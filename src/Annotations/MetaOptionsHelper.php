<?php

/**
 * This software package is licensed under New BSD license.
 *
 * @package maslosoft/mangan
 * @licence New BSD
 * @copyright Copyright (c) Piotr Masełkowski <pmaselkowski@gmail.com>
 * @copyright Copyright (c) Maslosoft
 * @copyright Copyright (c) Others as mentioned in code
 * @link http://maslosoft.com/mangan/
 */

namespace Maslosoft\Mangan\Annotations;

/**
 * MetaOptionsHelper
 * NOTE: This is only to get reference to current namespace, do not use it
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class MetaOptionsHelper
{

	const Ns = __NAMESPACE__;

	public function __construct()
	{
		throw new Exception(sprintf('Do not use %s, this is only heleper class for meta options', __CLASS__));
	}

}
