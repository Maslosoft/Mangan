<?php

namespace Maslosoft\Mangan\Example;

class ExampleAddress extends EmbeddedDocuemnt
{

	/**
	 * @Label('City')
	 * @LengthValidator(max = 60)
	 * @see http://en.wikipedia.org/wiki/Llanfairpwllgwyngyll
	 * @var string
	 */
	public $city;

	/**
	 * @Label('Street')
	 * @LengthValidator(max = 300)
	 * @var string
	 */
	public $street;

	/**
	 * @Label('House no.')
	 * @LengthValidator(max = 10)
	 * @var string
	 */
	public $house;

	/**
	 * @Label('Apartment no.')
	 * @LengthValidator(max = 10)
	 * @var string
	 */
	public $apartment;

	/**
	 * @Label('Zip code')
	 * @var string
	 */
	public $zip;

}
