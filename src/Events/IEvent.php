<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Maslosoft\Mangan\Events;

/**
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 * @property string $name Name of event
 * @property object $sender Sender
 * @property mixed $data Event data
 */
interface IEvent
{

	/**
	 * Ensure implementing class has this fields
	 */
	const RequireFields = [
		'name',
		'sender',
		'data'
	];

}
