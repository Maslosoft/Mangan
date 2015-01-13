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

namespace Maslosoft\Mangan\Options;

/**
 * AuthMechanism
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class AuthMechanism
{
const MongoDBCR = 'MONGODB-CR';
const X509 = 'X509';
const Plain = 'PLAIN';
const GSSAPI = 'GSSAPI';
}
