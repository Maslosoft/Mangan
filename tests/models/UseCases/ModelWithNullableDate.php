<?php


namespace Maslosoft\ManganTest\Models\UseCases;


use Maslosoft\Addendum\Interfaces\AnnotatedInterface;
use Maslosoft\Mangan\Sanitizers\DateSanitizer;
use Maslosoft\Mangan\Sanitizers\MongoObjectId;
use MongoDB\BSON\UTCDateTime as MongoDate;
use MongoDB\BSON\ObjectId as MongoId;

class ModelWithNullableDate implements AnnotatedInterface
{
	/**
	 * @Sanitizer(MongoObjectId)
	 * @see MongoObjectId
	 * @var MongoId
	 */
	public $_id;
	/**
	 * @Sanitizer(DateSanitizer)
	 * @Nullable
	 *
	 * @see DateSanitizer
	 * @var MongoDate|null
	 */
	public $date = null;
}