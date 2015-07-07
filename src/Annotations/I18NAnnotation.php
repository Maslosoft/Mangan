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

use InvalidArgumentException;
use Maslosoft\Mangan\Decorators\Property\I18NDecorator;
use Maslosoft\Mangan\Meta\I18NMeta;
use Maslosoft\Mangan\Meta\ManganPropertyAnnotation;

/**
 * This annotation indicates internationallized fields
 * @template I18N
 * @Target('property')
 * @author Piotr
 */
class I18NAnnotation extends ManganPropertyAnnotation
{

	public $value = true;
	public $allowDefault = false;
	public $allowAny = false;

	public function init()
	{
		if ($this->allowDefault && $this->allowAny)
		{
			throw new InvalidArgumentException(sprintf('Arguments "allowDefault" and "allowAny" for element "%s" in class "%s" cannot be both set true', $this->name, $this->_meta->type()->name));
		}
		$i18n = new I18NMeta();
		$i18n->enabled = $this->value;
		$i18n->allowDefault = $this->allowDefault;
		$i18n->allowAny = $this->allowAny;
		$this->_entity->i18n = $i18n;

		$this->_entity->decorators[] = I18NDecorator::class;
	}

}
