<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Maslosoft\ManganTest\Models\UseCases;

use Maslosoft\Mangan\Document;

/**
 * PageCell
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class PageCells extends Document
{

	/**
	 * @EmbeddedArray()
	 * @SafeValidator
	 * @var HtmlBlock
	 */
	public $widgets = [];

}
