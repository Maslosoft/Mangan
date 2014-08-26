<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Maslosoft\Mangan\Model;

use Maslosoft\Mangan\Document;
use MongoDate;
use MongoId;
use Yii;

/**
 * Session model. This can be used to display session data.
 * FIXME This must inherit from Maslosoft\Mangan\Document this is temporary solution
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class Session extends \Maslosoft\Components\MongoDocument
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
	 * @Readonly
	 * @var MongoDate
	 */
	public $dateTime = null;

	/**
	 * @Readonly
	 * @var MongoId
	 */
	public $userId = null;

	public function getCollectionName()
	{
		return Yii::app()->session->collectionName;
	}
}
