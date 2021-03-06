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

/**
 * ManganPropertyAnnotation
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
abstract class ManganPropertyAnnotation extends ManganAnnotation
{

	/**
	 * Annotations entity, it can be either class, property, or method
	 * Its concrete annotation implementation responsibility to decide what to do with it.
	 * @return DocumentPropertyMeta
	 */
	public function getEntity()
	{
		return parent::getEntity();
	}

}
