<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Maslosoft\Mangan\Helpers;

use Maslosoft\Mangan\EmbeddedDocument;
use Maslosoft\Mangan\Events\ClassNotFound;
use Yii;

/**
 * Helper which can be used to resolve not found classes of embedded documents.
 * This can be usefull if embedded document class was renamed
 * Use this in class constructor, ie:
 * <pre>
 * public function __construct($scenario = 'insert', $lang = '')
 *	{
 *		parent::__construct($scenario, $lang);
 *		$resolver = new Maslosoft\Mangan\Helpers\NotFoundResolver($this);
 *		$resolver->classMap = [
 *			'LegacyName' => 'Company\Models\UberName'
 *		];
 *	}
 * </pre>
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class NotFoundResolver
{

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

	public function __construct(EmbeddedDocument $document, $classMap = [])
	{
		if ($document->hasEvent('onClassNotfound'))
		{
			$onClassNotFound = function($event)
			{
				return $this->_onClassNotFound($event);
			};
			$onClassNotFound->bindTo($this);
			$document->onClassNotFound = $onClassNotFound;
		}
		$this->classMap = $classMap;
	}

	private function _onClassNotFound(ClassNotFound $event)
	{

		if(isset($this->classMap[$event->notFound]))
		{
			Yii::trace(sprintf('Not found class `%s`, replaced with %s', $event->notFound, $event->replacement), 'Maslosoft.Mangan.Helpers.NotFoundResolver');
			$event->replacement = $this->classMap[$event->notFound];
		}
		return $event->replacement;
	}

}
