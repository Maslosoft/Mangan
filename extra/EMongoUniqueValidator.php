<?php

/**
 * @author Ianaré Sévi
 * @author Dariusz Górecki <darek.krk@gmail.com>
 * @author Invenzzia Group, open-source division of CleverIT company http://www.invenzzia.org
 * @copyright 2011 CleverIT http://www.cleverit.com.pl
 * @license New BSD license
 * @version 1.3
 * @category ext
 * @package ext.YiiMongoDbSuite
 */

/**
 * @since v1.1
 */
class EMongoUniqueValidator extends CValidator
{
	public $allowEmpty=true;

	public function validateAttribute($object, $attribute)
	{
		$value = $object->{$attribute};
		if($this->allowEmpty && ($value === null || $value === ''))
			return;

		$criteria = new EMongoCriteria;
		if(!$object->getIsNewRecord())
			$criteria->addCond('_id', '!=', $object->getPrimaryKey());
		$criteria->addCond($attribute, '==', $value);
		$count = $object->model()->count($criteria);

		if($count !== 0)
			$this->addError(
				$object,
				$attribute,
				Yii::t('yii', '{attribute} is not unique in DB.')
			);
	}
}
