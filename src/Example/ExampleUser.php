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

namespace Maslosoft\Mangan\Example;

use Maslosoft\Mangan\Document;
use Maslosoft\Mangan\Model\Image;

class ExampleUser extends Document
{

	/**
	 * @Label('User name')
	 * @RequiredValidator
	 * @var string
	 */
	public $username;

	/**
	 * @Label('E-mail')
	 * @RequiredValidator
	 * @var string
	 */
	public $email;

	/**
	 * @Label('Personal ID number')
	 * @RequiredValidator
	 * @var string
	 */
	public $personalNumber;

	/**
	 * @Label('First name')
	 * @SafeValidator
	 * @var string
	 */
	public $firstName;

	/**
	 * @Label('Last name')
	 * @SafeValidator
	 * @var string
	 */
	public $lastName;

	/**
	 * @Embedded('Maslosoft\Mangan\Example\ExampleAddress')
	 * @var ExampleAddress
	 */
	public $address;

	/**
	 * @Embedded('Maslosoft\Mangan\Model\Image')
	 * @var Image
	 */
	public $avatar;
}
