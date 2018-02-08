<?php
/**
 * Created by PhpStorm.
 * User: peter
 * Date: 08.02.18
 * Time: 10:28
 */

namespace Maslosoft\Mangan\Events\Handlers;


use Maslosoft\Addendum\Utilities\ClassChecker;
use Maslosoft\Mangan\Helpers\ParentChildTrashHandlers;
use Maslosoft\Mangan\Interfaces\EventHandlersInterface;

class ParentChildTrashHandler implements EventHandlersInterface
{
	public $parentClass = '';

	public $childClass = '';

	/**
	 * This method will be called only once, and should setup event handlers.
	 */
	public function setupHandlers()
	{
		assert(!empty($this->parentClass));
		assert(!empty($this->childClass));
		assert(ClassChecker::exists($this->parentClass));
		assert(ClassChecker::exists($this->childClass));
		$handler = new ParentChildTrashHandlers;
		$handler->registerParent($this->parentClass, $this->childClass);
		$handler->registerChild($this->childClass, $this->parentClass);
	}
}