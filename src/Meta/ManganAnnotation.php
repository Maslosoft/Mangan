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

use Exception;
use Maslosoft\Addendum\Collections\MetaAnnotation;
use Maslosoft\Mangan\Helpers\AnnotationDefaults;

/**
 * ManganAnnotation
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
abstract class ManganAnnotation extends MetaAnnotation
{
	public function __construct($data = [], $target = false)
	{
		parent::__construct($data, $target);
		AnnotationDefaults::apply($this, $data);
	}

	/**
	 * Model metadata object
	 * @return ManganMeta
	 */
	public function getMeta()
	{
		return parent::getMeta();
	}

}
