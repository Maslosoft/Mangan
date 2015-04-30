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

namespace Maslosoft\Mangan\Annotations;

use Maslosoft\Mangan\Meta\ManganPropertyAnnotation;

/**
 * Label
 * Set translated entity 'label' field
 * @template Label('${text}')
 */
class LabelAnnotation extends ManganPropertyAnnotation
{

	public $value = '';

	public function init()
	{
		$this->_entity->label = $this->value;
	}

}
