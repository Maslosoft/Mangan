<?php

/**
 * This software package is licensed under New BSD license.
 *
 * @package maslosoft/mangan
 * @licence New BSD
 * @copyright Copyright (c) Piotr Masełkowski <pmaselkowski@gmail.com>
 * @copyright Copyright (c) Maslosoft
 * @copyright Copyright (c) Others as mentioned in code
 * @link http://maslosoft.com/mangan/
 */

namespace Maslosoft\Mangan\Model;

use Maslosoft\Addendum\Interfaces\IAnnotated;
use MongoDate;
use MongoId;

/**
 * Session model. This can be used to display session data.
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class Session implements IAnnotated
{

	/**
	 * User agent
	 * @Label('Browser')
	 * @Readonly
	 * @var string
	 */
	public $browser = '';

	/**
	 * Ip address
	 * @Label('IP Address')
	 * @Readonly
	 * @var string
	 */
	public $ip = '';

	/**
	 * Activity datetime
	 * @Label('Last activity')
	 * @Sanitizer('MongoDate')
	 * @Readonly
	 * @var MongoDate
	 */
	public $dateTime = null;

	/**
	 * @Readonly
	 * @Sanitizer('MongoStringId')
	 * @var MongoId
	 */
	public $userId = null;

}
