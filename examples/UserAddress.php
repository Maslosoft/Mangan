<?php
class UserAddress extends EMongoEmbeddedDocuemnt
{
	public $city;
	public $street;
	public $apartment;
	public $zip;

	public function rules()
	{
		return [
			['city, street, house', 'length', 'max'=>255],
			['house, apartment, zip', 'length', 'max'=>10],
		];
	}

	/**
	 * Returns attribute labels for each public variable that will be stored
	 * as key in the database. It is defined just as normal with SQL models.
	 * @return array validation rules for model attributes.
	 */
	public function attributeLabels()
	{
		return [
			'zip' => 'Postal Code',
		];
	}
}
