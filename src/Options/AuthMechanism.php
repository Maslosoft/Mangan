<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
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
