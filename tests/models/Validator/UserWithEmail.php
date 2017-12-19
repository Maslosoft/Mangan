<?php

namespace Maslosoft\ManganTest\Models\Validator;

use Maslosoft\Mangan\Document;

/**
 * UserWithEmail
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class UserWithEmail extends Document
{

	/**
	 * NOTE: For sake of compatibility let's name it emails,
	 * but it must contain one sub object only.
	 * @Label('Extra e-mail')
	 * @Embedded(UserEmail)
	 *
	 * @var UserEmail
	 */
	public $emails = null;

	/**
	 * NOTE: This field name is purposely same as
	 * embedded document field name.
	 * @Label('Primary e-mail')
	 * @SafeValidator
	 * @var string
	 */
	public $email = '';

}
