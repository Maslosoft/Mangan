<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Maslosoft\Mangan\Interfaces;

/**
 * Implement this interface to allow dynamic/callable collection names in documents
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
interface IWithCollectionName
{
	public function getCollectionName();
}
