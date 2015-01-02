<?php

namespace Maslosoft\Mangan\Annotations;

use InvalidArgumentException;
use Maslosoft\Addendum\Collections\MetaAnnotation;
use Maslosoft\Mangan\Meta\I18NMeta;

/**
 * This annotation indicates internationallized fields
 * @template I18N
 * @Target('property')
 * @author Piotr
 */
class I18NAnnotation extends MetaAnnotation
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
		$i18n = new I18NMeta($this->_entity);
		$i18n->enabled = $this->value;
		$i18n->allowDefault = $this->allowDefault;
		$i18n->allowAny = $this->allowAny;
		$this->_entity->i18n = $i18n;
		if (count($this->_entity->decorators))
		{
			throw new ManganException('I18N Annotation must be very first annotation');
		}
		$this->_entity->decorators[] = I18NDecorator::class;
	}

}
