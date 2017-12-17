<?php
/**
 * Created by PhpStorm.
 * User: peter
 * Date: 17.12.17
 * Time: 19:41
 */

namespace Maslosoft\Mangan\Model;

use Maslosoft\Addendum\Interfaces\AnnotatedInterface;

/**
 * Class Geo
 * @package Maslosoft\Mangan\Model
 */
class Geo implements AnnotatedInterface
{
	const Point = 'Point';
	const LineString = 'LineString';
	const Polygon = 'Polygon';
	const MultiPoint = 'MultiPoint';
	const MultiLineString = 'MultiLineString';
	const MultiPolygon = 'MultiPolygon';

	/**
	 * @var string
	 */
	public $type = self::Point;

	/**
	 * Coordinates of this Geographical object
	 * @var array
	 */
	public $coordinates = [];
}