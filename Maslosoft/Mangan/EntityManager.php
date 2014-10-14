<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Maslosoft\Mangan;

/**
 * EntityManager
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class EntityManager
{

	/**
	 * Model
	 * @var Document
	 */
	public $model = null;

	public function __construct(Document $model)
	{
		$this->model = $model;
	}

	public function save()
	{

	}

	public function insert()
	{

	}

	public function update()
	{
		
	}
}
