<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Maslosoft\ManganTest\Models\Event;

use Maslosoft\Mangan\Document;
use Maslosoft\Mangan\Events\Event;
use Maslosoft\Mangan\Helpers\ParentChildTrashHandlers;
use Maslosoft\Mangan\Interfaces\TrashInterface;
use Maslosoft\Mangan\Traits\Model\TrashableTrait;

/**
 * ParentDocument
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class ParentDocument extends Document
{

	use TrashableTrait;

	public $title = '';

	public function __toString()
	{
		return $this->title;
	}

}
