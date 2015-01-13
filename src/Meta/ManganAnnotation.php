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

namespace Maslosoft\Mangan\Meta;

use Maslosoft\Addendum\Collections\MetaAnnotation;

/**
 * ManganAnnotation
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
abstract class ManganAnnotation extends MetaAnnotation
{

	/**
	 * Model metadata object
	 * @var ManganMeta
	 */
	protected $_meta = null;

}
