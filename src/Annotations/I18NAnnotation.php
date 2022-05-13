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

use InvalidArgumentException;
use Maslosoft\Addendum\Helpers\ParamsExpander;
use Maslosoft\Mangan\Decorators\Property\I18NDecorator;
use Maslosoft\Mangan\Meta\I18NMeta;
use Maslosoft\Mangan\Meta\ManganPropertyAnnotation;
use Maslosoft\ManganTest\Models\ModelWithI18NAllowAnyAndDefault;

/**
 * This annotation indicates internationalized fields. This is not limited to text fields. Any kind of field can
 * be set to be internationalized, having value dependent on selected language.
 *
 * It is possible to use fallback language from default application language,
 * any available value or both.
 *
 * Use fallback to default language:
 * ```
 * @I18N('allowDefault' = true)
 * ```
 *
 * Use fallback to value in any available language:
 * ```
 * @I18N('allowAny' = true)
 * ```
 *
 * Use fallback to default language if set, or any other language if any value is available:
 * ```
 * @I18N('allowDefault' = true, `allowAny` = true)
 * ```
 *
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
