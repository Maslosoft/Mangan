<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Maslosoft\ManganTest\Models;

use Maslosoft\Mangan\Document;

/**
 * ModelWithLabel
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class ModelWithLabel extends Document
{

	const Title = 'My Title';
	const TitleLabel = 'Model Title';
	const State = 'Alabama';
	const StateLabel = 'State of residence';

	/**
	 * @Label('Model Title');
	 * @var string
	 */
	public $title = self::Title;

	/**
	 * @Label('State of residence');
	 * @var string
	 */
	public $state = self::State;

}
