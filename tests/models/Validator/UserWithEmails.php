<?php

namespace Maslosoft\ManganTest\Models\Validator;

use Maslosoft\Mangan\Document;

/**
 * UserWithEmails
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class UserWithEmails extends Document
{

	/**
	 * @Label('Extra e-mails')
	 * @EmbeddedArray(UserEmail)
	 *
	 * @var UserEmail[]
	 */
	public $emails = [];

	/**
	 * NOTE: This field name is purposely same as
	 * embedded document field name.
	 * @Label('Primary e-mail')
	 * @SafeValidator
	 * @var string
	 */
	public $email = '';

}
