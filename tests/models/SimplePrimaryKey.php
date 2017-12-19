<?php

namespace Maslosoft\ManganTest\Models;

use Maslosoft\Addendum\Interfaces\AnnotatedInterface;

/**
 * Simple primary model
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class SimplePrimaryKey implements AnnotatedInterface
{

	/**
	 * @Maslosoft\Mangan\Annotations\PrimaryKey
	 * @Sanitizer('MongoStringId')
	 */
	public $primaryKey = '';
	public $title = '';

}
