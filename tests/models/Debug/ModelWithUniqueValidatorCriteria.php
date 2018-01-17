<?php
/**
 * Created by PhpStorm.
 * User: peter
 * Date: 17.01.18
 * Time: 18:07
 */

namespace Maslosoft\ManganTest\Models\Debug;


use Maslosoft\Mangan\Document;

class ModelWithUniqueValidatorCriteria extends Document
{
	/**
	 * @Label('Phone')
	 * @UniqueValidator('criteria' => ['conditions' => ['active' => ['==' => true]]], 'message' => @Label('There is already active {attribute} with code {value}'))
	 * @var string
	 */
	public $code = '';

	public $active = false;
}