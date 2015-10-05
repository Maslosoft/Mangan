<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Maslosoft\Mangan\Interfaces;

use MongoCursor;

/**
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
interface ProfillerInterface
{

	/**
	 * Profile any data
	 * @param string $data
	 */
	public function profile($data);

	/**
	 * Profile cursor
	 * @param MongoCursor $cursor
	 */
	public function cursor(MongoCursor $cursor);
}
