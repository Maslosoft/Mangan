<?php

namespace Maslosoft\ManganTest\Models\Validator;

use Maslosoft\Mangan\Document;

/**
 * UserEmail
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class UserEmail extends Document
{

	/**
	 * @Label('E-mail')
	 *
	 * @RequiredValidator
	 * @EmailValidator
	 *
	 * @var string
	 */
	public $email = '';

}
