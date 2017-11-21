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

namespace Maslosoft\Mangan\Annotations;

use function codecept_debug;
use InvalidArgumentException;
use Maslosoft\Addendum\Helpers\ParamsExpander;
use Maslosoft\Mangan\Decorators\Property\I18NDecorator;
use Maslosoft\Mangan\Meta\I18NMeta;
use Maslosoft\Mangan\Meta\ManganPropertyAnnotation;
use Maslosoft\ManganTest\Models\ModelWithI18NAllowAnyAndDefault;

/**
 * This annotation indicates internationalized fields
 * @template I18N
 * @Target('property')
 * @author Piotr
 */
class I18NAnnotation extends ManganPropertyAnnotation
{

	public $value = [];

	public $allowDefault = null;

	public $allowAny = null;

	public function init()
	{
		$data = ParamsExpander::expand($this, ['allowDefault', 'allowAny']);
		foreach ($data as $name => $value)
		{
			$this->$name = $value;
		}

		$i18n = new I18NMeta();
		$i18n->enabled = true;
		$i18n->allowDefault = (bool)$this->allowDefault;
		$i18n->allowAny = (bool)$this->allowAny;
		$this->getEntity()->i18n = $i18n;

		$this->getEntity()->decorators[] = I18NDecorator::class;
	}

}
