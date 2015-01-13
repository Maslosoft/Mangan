<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Maslosoft\Mangan\Model;

use Maslosoft\Mangan\Interfaces\IModel;
use MongoDate;
use MongoId;

/**
 * Session model. This can be used to display session data.
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class Session implements IModel
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
