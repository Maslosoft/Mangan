<?php

/**
 * This software package is licensed under AGPL or Commercial license.
 *
 * @package maslosoft/mangan
 * @licence AGPL or Commercial
 * @copyright Copyright (c) Piotr Masełkowski <pmaselkowski@gmail.com>
 * @copyright Copyright (c) Maslosoft
 * @copyright Copyright (c) Others as mentioned in code
 * @link https://maslosoft.com/mangan/
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