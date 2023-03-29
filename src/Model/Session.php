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

namespace Maslosoft\Mangan\Model;

use Maslosoft\Addendum\Interfaces\AnnotatedInterface;
use MongoDB\BSON\UTCDateTime as MongoDate;
use MongoDB\BSON\ObjectId as MongoId;

/**
 * Session model. This can be used to display session data.
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class Session implements AnnotatedInterface
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
