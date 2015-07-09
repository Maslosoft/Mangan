<?php

/**
 * This software package is licensed under AGPL or Commercial license.
 *
 * @package maslosoft/mangan
 * @licence AGPL or Commercial
 * @copyright Copyright (c) Piotr Masełkowski <pmaselkowski@gmail.com>
 * @copyright Copyright (c) Maslosoft
 * @copyright Copyright (c) Others as mentioned in code
 * @link http://maslosoft.com/mangan/
 */

namespace Maslosoft\Mangan\Helpers;

use Maslosoft\Addendum\Interfaces\AnnotatedInterface;
use Maslosoft\Mangan\EmbeddedDocument;
use Maslosoft\Mangan\Events\ClassNotFound;
use Maslosoft\Mangan\Events\Event;
use Maslosoft\Mangan\Mangan;

/**
 * # Not found resolver
 * Helper which can be used to resolve not found classes of embedded documents.
 * This can be usefull if embedded document class was renamed in code,
 * but was left in database.
 *
 * Use this in document class constructor, ie:
 * ```php
 * public function __construct()
 * 	{
 * 		$resolver = new Maslosoft\Mangan\Helpers\NotFoundResolver($this);
 * 		$resolver->classMap = [
 * 			'LegacyName' => 'Company\Models\UberName'
 * 		];
 * 	}
 * ```
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class NotFoundResolver
{

	const EventClassNotFound = 'classNotFound';

	/**
	 * Class map in form:
	 * ``php
	 * [
	 * 		'Old\Class\Name' => 'New\Class\Name'
	 * ]
	 * ```
	 * @var string[]
	 */
	public $classMap = [];

	/**
	 * Document instance
	 * @var EmbeddedDocument
	 */
	protected $document = null;

	/**
	 * First param is document which could have some obsolete classes stored in database.
	 * Class map should map obsolete names with new names.
	 *
	 * Key should be obsolete name, while value current name:
	 * ```php
	 * $classMap = [
	 * 		'LegacyName' => 'Company\Models\UberName',
	 * 		'SomeOtherEmbedded' => Vendor\Brand\NewName::class
	 * ]
	 * ```
	 * @param AnnotatedInterface $document
	 * @param string[] $classMap
	 */
	public function __construct(AnnotatedInterface $document, $classMap = [])
	{
		$onClassNotFound = function($event)
		{
			return $this->_onClassNotFound($event);
		};
		$onClassNotFound->bindTo($this);
		Event::on($document, self::EventClassNotFound, $onClassNotFound);
		$this->classMap = $classMap;
	}

	/**
	 * Event handler. This return substitution class name.
	 * @param ClassNotFound $event
	 * @return string
	 */
	private function _onClassNotFound(ClassNotFound $event)
	{
		if (isset($this->classMap[$event->notFound]))
		{
			$message = sprintf('Not found class `%s`, replaced with %s', $event->notFound, $event->replacement);
			Mangan::fromModel($event->sender)->getLogger()->notice($message);
			$event->replacement = $this->classMap[$event->notFound];
			$event->handled = true;
		}
		return $event->replacement;
	}

}
