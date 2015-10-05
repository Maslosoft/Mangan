<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Maslosoft\Mangan\Profillers;

use Maslosoft\Mangan\Interfaces\ManganAwareInterface;
use Maslosoft\Mangan\Interfaces\ProfillerInterface;
use Maslosoft\Mangan\Mangan;
use MongoCursor;

/**
 * Profiller
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class LoggingProfiller implements ProfillerInterface, ManganAwareInterface
{

	/**
	 * Mangan instance
	 * @var Mangan
	 */
	private $mangan = null;

	/**
	 * Proifile any data
	 * @param string $data
	 */
	public function profile($data)
	{
		$this->mangan->getLogger()->info($data);
	}

	/**
	 * Profile cursor
	 * @param MongoCursor $cursor
	 */
	public function cursor(MongoCursor $cursor)
	{
		$this->mangan->getLogger()->info(var_export($cursor->explain(), true));
	}

	/**
	 * Set mangan instance
	 * @param Mangan $mangan
	 * @return LoggingProfiller
	 */
	public function setMangan(Mangan $mangan)
	{
		$this->mangan = $mangan;
		return $this;
	}

}
