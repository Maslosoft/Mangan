<?php
/**
 * Created by PhpStorm.
 * User: peter
 * Date: 22.10.18
 * Time: 20:58
 */

namespace Maslosoft\Mangan\Events;

use Maslosoft\Addendum\Interfaces\AnnotatedInterface;

/**
 * Create event handler for this event
 * to try to recover from unknown document state.
 *
 * NOTE: Event *must* be handled for this to work.
 *
 * The handler can use `getData` to inspect what
 * was passed into `Transformator::toModel()` method,
 * adjust the data, or replace with completely new one and
 * pass it back to `setData`.
 *
 * Example use case:
 *
 * 1. Field was array of languages
 * 2. After application refactoring it was decided to change it to embedded array
 * 3. Old data exists containing only languages
 *
 * Solution:
 *
 * Event handler for UnknownDocumentType adds `_class` field and
 * set values properly.
 *
 * Class UnknownDocumentType
 * @package Maslosoft\Mangan\Events
 */
class UnknownDocumentType extends ModelEvent
{
	const EventName = 'unknownDocumentType';
	private $modelData = [];

	/**
	 * Parent Model
	 * @var AnnotatedInterface|null
	 */
	public $parent = null;

	/**
	 * Parent model field name
	 * @var string
	 */
	public $field = '';

	/**
	 * Get data from error. This will
	 * be called also when trying to recover.
	 * @return array
	 */
	public function getData()
	{
		return $this->modelData;
	}

	/**
	 * @param array $data
	 */
	public function setData($data)
	{
		$this->modelData = $data;
	}


}