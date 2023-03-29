<?php
/**
 * Created by PhpStorm.
 * User: peter
 * Date: 23.01.18
 * Time: 10:05
 */

namespace Maslosoft\ManganTest\Models\Issues;


use Maslosoft\Addendum\Interfaces\AnnotatedInterface;
use Maslosoft\Mangan\Sanitizers\MongoObjectId;
use MongoDB\BSON\ObjectId as MongoId;

/**
 * Class ModelWithId63
 *
 * @see https://github.com/Maslosoft/Mangan/issues/63
 * @package Maslosoft\ManganTest\Models\Issues
 */
class ModelWithId63 implements AnnotatedInterface
{
	/**
	 * @Sanitizer(MongoObjectId)
	 *
	 * @see MongoObjectId
	 * @var MongoId
	 */
	public $_id = null;
	/**
	 * @Sanitizer(MongoObjectId)
	 * @PrimaryKey
	 * @see MongoObjectId
	 * @var MongoId
	 */
	public $userId = null;

	/**
	 * @PrimaryKey
	 * @var string
	 */
	public $widgetId = '';
}