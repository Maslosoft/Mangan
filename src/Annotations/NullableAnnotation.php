<?php


namespace Maslosoft\Mangan\Annotations;

use Maslosoft\Mangan\Meta\ManganPropertyAnnotation;

/**
 * If true, value will be set to `null` **only** if it is one of:
 *
 * 1. Empty string
 * 2. `null`
 * 3. Empty array
 * 4. Integer `0`
 * 5. Float `0.0`
 * 6. Boolean `false`
 * 7. String `"0"`
 *
 * This will use the PHP's built in `empty` construct.
 *
 * This annotation default to true, and using false might have sense
 * on on overridden properties.
 *
 * Example usage:
 *
 * ```php
 * @Nullable
 * ```
 *
 * Example of overriding nullable state:
 *
 * ```php
 * // On base class
 * @Nullable
 * // On inherited property
 * @Nullable(false)
 * ```
 *
 * @Target('property')
 * @template Persistent(${false})
 * @author Piotr
 */
class NullableAnnotation extends ManganPropertyAnnotation
{

	public $value = true;

	public function init()
	{
		$this->getEntity()->nullable = (bool) $this->value;
	}

}