<?php


namespace Maslosoft\ManganTest\Models\Criteria;


use Maslosoft\Addendum\Interfaces\AnnotatedInterface;
use Maslosoft\Mangan\Sanitizers\None;
use Maslosoft\Mangan\Sort;

class ModelWithCustomPkAndTypedProperty implements AnnotatedInterface
{

	/**
	 * NOTE: Have sanitizer rounding to full days, without time
	 *
	 * Example value:
	 * ```
	 * '2017-08-12'
	 * ```
	 *
	 * @Index(Sort::SortAsc)
	 * @Index(Sort::SortDesc)
	 * @PrimaryKey
	 *
	 * @see Sort
	 * @var ?string
	 */
	public ?string $date = '';

	/**
	 * Arrays containing daily visits by browsers.
	 *
	 * Browser name is a key, value is a number of visits.
	 *
	 * Example array:
	 * ```
	 * [
	 *        'Firefox' => 12,
	 *        'Chrome' => 22,
	 * ]
	 * ```
	 *
	 * @Sanitizer(None)
	 * @see None
	 * @var int[]
	 */
	public array $browsers = [];

	/**
	 * Arrays containing daily visits by operating systems.
	 *
	 * OS name is a key, value is a number of visits.
	 *
	 * Example array:
	 * ```
	 * [
	 *        'Windows' => 12,
	 *        'Linux' => 22,
	 * ]
	 * ```
	 *
	 * @Sanitizer(None)
	 * @var int[]
	 */
	public array $oses = [];

	/**
	 * Arrays containing daily visits by groups.
	 *
	 * Group name is a key, value is a number of visits.
	 *
	 * Example array:
	 * ```
	 * [
	 *        'public' => 12,
	 *        'registered' => 22,
	 *        'admin' => 2,
	 * ]
	 * ```
	 *
	 * @Sanitizer(None)
	 * @var int[]
	 */
	public array $groups = [];

	/**
	 * Arrays containing daily visits by controllers.
	 *
	 * ModuleID@ControllerID is a key, value is a number of visits.
	 *
	 * Example array:
	 * ```
	 * [
	 *        'content@page' => 12,
	 *        'content@blog' => 22,
	 *        'content@blogPost' => 2,
	 * ]
	 * ```
	 * @Sanitizer(None)
	 *
	 * @see None
	 * @var int[]
	 */
	public array $controllers = [];
}