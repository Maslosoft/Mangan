<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Maslosoft\Mangan\Interfaces;

/**
 * Implement this interface on your model to anable I18N fields support
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
interface I18NAble
{

	public function getLang();

	public function setLang();
}
