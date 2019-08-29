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

use Maslosoft\Addendum\Helpers\ParamsExpander;
use Maslosoft\Mangan\Decorators\DbRefDecorator;
use Maslosoft\Mangan\Decorators\EmbedRefDecorator;
use Maslosoft\Mangan\Meta\DbRefMeta;
use Maslosoft\Mangan\Meta\ManganPropertyAnnotation;
use UnexpectedValueException;

/**
 * DB reference annotation
 *
 * Most simple usage:
 * ```
 * @DbRef(Vendor\Package\ClassLiteral)
 * ```
 *
 * Disable updates, long notation:
 * ```
 * @DbRef(Vendor\Package\ClassLiteral, 'updatable' = false)
 * ```
 *
 * Disable updates, short notation:
 * ```
 * @DbRef(Vendor\Package\ClassLiteral, false)
 * ```
 *
 * NOTE: Nullable requires field to be not `updatable`
 * Nullable, long notation:
 * ```
 * @DbRef(Vendor\Package\ClassLiteral, 'updatable' = false)
 * ```
 *
 * Nullable, short notation:
 * ```
 * @DbRef(Vendor\Package\ClassLiteral, false, false)
 * ```
 *
 * @template DbRef(${class}, ${updatable})
 *
 * @Conflicts('Embedded')
 * @Conflicts('EmbeddedArray')
 * @Conflicts('DbRefArray')
 * @Conflicts('Related')
 * @Conflicts('RelatedArray')
 *
 * @Target('property')
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class DbRefAnnotation extends ManganPropertyAnnotation
{

	public $class;
	public $updatable;
	public $nullable;
	public $value;

	public function init()
	{
		$refMeta = $this->getDbRefMeta();
		$refMeta->single = true;
		$refMeta->isArray = false;
		$this->getEntity()->dbRef = $refMeta;
		$this->getEntity()->propagateEvents = true;
		$this->getEntity()->owned = true;
		$this->getEntity()->decorators[] = DbRefDecorator::class;
		$this->getEntity()->decorators[] = EmbedRefDecorator::class;
	}

	protected function getDbRefMeta()
	{
		$data = ParamsExpander::expand($this, ['class', 'updatable', 'nullable']);

		$params = [
			$this->getMeta()->type()->name,
			$this->getEntity()->name
		];

		$msg = vsprintf("Parameter `updatable` must be of type `boolean` (or be omitted) on %s:%s", $params);
		$msg2 = vsprintf("Parameter `nullable` must be of type `boolean` (or be omitted) on %s:%s", $params);

		assert(empty($data['updatable']) || is_bool($data['updatable']), new UnexpectedValueException($msg));
		assert(empty($data['nullable']) || is_bool($data['nullable']), new UnexpectedValueException($msg2));

		$refMeta = new DbRefMeta($data);
		if (!$refMeta->class)
		{
			$refMeta->class = $this->getMeta()->type()->name;
		}
		if($refMeta->updatable && $refMeta->nullable)
		{
			$msg3 = vsprintf("Both parameter `nullable` and `updatable` cannot be set to `true` on %s:%s.", $params);
			$msg3 .= 'This feature is not implemented';
			throw new UnexpectedValueException($msg3);
		}
		$this->getEntity()->updatable = $refMeta->updatable;
		$this->getEntity()->nullable = $refMeta->nullable;
		return $refMeta;
	}

}
