<?php

class EMongoUniqueValidator extends CValidator
{
	public $allowEmpty=true;

	public function validateAttribute($object, $attribute)
	{
		$value = $object->{$attribute};
		if($this->allowEmpty && ($value === null || $value === ''))
			return;

		$criteria = new EMongoCriteria;
		$criteria->{$attribute} = $value;
		$count = $object::model()->count($criteria);

		if($count !== 0)
			$this->addError(
				$object,
				$attribute,
				Yii::t('yii', '{attribute} is not unique in DB.')
			);
	}
}