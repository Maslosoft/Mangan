<?php

/**
 * This software package is licensed under New BSD license.
 *
 * @package maslosoft/mangan
 * @licence New BSD
 * @copyright Copyright (c) Piotr Masełkowski <pmaselkowski@gmail.com>
 * @copyright Copyright (c) Maslosoft
 * @copyright Copyright (c) Others as mentioned in code
 * @link http://maslosoft.com/mangan/
 */

namespace Maslosoft\Mangan\Helpers;

use Maslosoft\Addendum\Interfaces\IAnnotated;
use Maslosoft\Mangan\EmbeddedDocument;
use Maslosoft\Mangan\Events\ClassNotFound;
use Maslosoft\Mangan\Events\Event;

/**
 * Helper which can be used to resolve not found classes of embedded documents.
 * This can be usefull if embedded document class was renamed
 * Use this in class constructor, ie:
 * <pre>
 * public function __construct($scenario = 'insert', $lang = '')
 * 	{
 * 		parent::__construct($scenario, $lang);
 * 		$resolver = new Maslosoft\Mangan\Helpers\NotFoundResolver($this);
 * 		$resolver->classMap = [
 * 			'LegacyName' => 'Company\Models\UberName'
 * 		];
 * 	}
 * </pre>
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class NotFoundResolver
{

	const EventClassNotFound = 'classNotFound';

	/**
	 * Class map in form:
	 * ['Old\Class\Name' => 'New\Class\Name']
	 * @var string[]
	 */
	public $classMap = [];

	/**
	 * Document instance
	 * @var EmbeddedDocument
	 */
	protected $document = null;

	public function __construct(IAnnotated $document, $classMap = [])
	{
		$onClassNotFound = function($event)
		{
			return $this->_onClassNotFound($event);
		};
		$onClassNotFound->bindTo($this);
		Event::on($document, self::EventClassNotFound, $onClassNotFound);
		$this->classMap = $classMap;
	}

	private function _onClassNotFound(ClassNotFound $event)
	{
		if (isset($this->classMap[$event->notFound]))
		{
//			Yii::trace(sprintf('Not found class `%s`, replaced with %s', $event->notFound, $event->replacement), 'Maslosoft.Mangan.Helpers.NotFoundResolver');
			$event->replacement = $this->classMap[$event->notFound];
			$event->handled = true;
		}
		return $event->replacement;
	}

}
