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

use function is_a;
use Maslosoft\Addendum\Helpers\ParamsExpander;
use Maslosoft\Addendum\Utilities\ClassChecker;
use Maslosoft\Mangan\Interfaces\CriteriaInterface;
use Maslosoft\Mangan\Interfaces\ScopeInterface;
use Maslosoft\Mangan\Meta\ManganTypeAnnotation;

/**
 * Annotation can be used to add extra scope
 * for model. Scopes will add extra criteria when
 * finding, updating and removing model.
 *
 * The scope is a simple class implementing `ScopeInterface`.
 * The `ScopeInterface` method `getCriteria` must return `CriteriaInterface`,
 * most likely `Criteria` instance.
 *
 * Usage:
 *
 * ```
 * @Scope(EmbeddedClassName)
 * ```
 *
 * @Target('class')
 * @template Scope(${defaultClassName})
 * @see ScopeInterface
 * @see CriteriaInterface
 * @author Piotr
 */
class ScopeAnnotation extends ManganTypeAnnotation
{

	public $value = true;

	public function init()
	{
		$data = ParamsExpander::expand($this, ['class']);
		$className = $data['class'];
		assert(ClassChecker::exists($className));
		assert(is_a($className, ScopeInterface::class, true));
		$this->getEntity()->scopes[] = $className;
	}

}
