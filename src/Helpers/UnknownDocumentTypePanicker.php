<?php
/**
 * Created by PhpStorm.
 * User: peter
 * Date: 22.10.18
 * Time: 21:28
 */

namespace Maslosoft\Mangan\Helpers;

use Maslosoft\Mangan\Events\Event;
use Maslosoft\Mangan\Events\UnknownDocumentType;
use Maslosoft\Mangan\Exceptions\TransformatorException;
use function array_key_exists;
use function get_class;
use function gettype;
use function is_object;
use function sprintf;

/**
 * This class will try to do something on unknown documents
 * or will panic.
 *
 * @package Maslosoft\Mangan\Helpers
 */
class UnknownDocumentTypePanicker
{
	public static function tryHandle(&$data, $parent, $parentField)
	{
		$className = '';
		$handled = false;
		if(!empty($parent))
		{
			$event = new UnknownDocumentType();
			$event->setData($data);
			$event->parent = $parent;
			$event->field = $parentField;

			$handled = Event::handled($parent, UnknownDocumentType::EventName, $event);
			if($handled)
			{
				$data = $event->getData();
				if(empty($data['_class']))
				{
					$handled = false;
				}
				else
				{
					$className = $data['_class'];
				}
			}
		}
		if(!$handled)
		{
			$params = [];
			if(!empty($parent) && is_object($parent))
			{
				$params[] = sprintf('on model `%s`', get_class($parent));
			}
			if(!empty($parentField))
			{
				$params[] = sprintf('on field `%s`', $parentField);
			}

			if(array_key_exists('data', $data))
			{
				$params[] = sprintf('got type `%s`', gettype($data['data']));

				if (is_object($data['data']))
				{
					$params[] = sprintf('instance of `%s`', get_class($data['data']));
				}
			}
			throw new TransformatorException('Could not determine document type ' . implode(', ', $params));
		}
		return $className;
	}
}