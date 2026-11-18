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

namespace Maslosoft\Mangan\Options;

use Maslosoft\Mangan\Helpers\PropertyMaker;
use Maslosoft\Mangan\Mangan;
use Maslosoft\Mangan\Transaction;
use MongoDB\Driver\WriteConcern;
use Maslosoft\Mangan\Meta\DocumentTypeMeta;
use Maslosoft\Mangan\Meta\ManganMeta;
use Maslosoft\Mangan\Traits\Defaults\MongoClientOptions;

/**
 * EntityOptions
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class EntityOptions
{

	use MongoClientOptions;

	/**
	 *
	 * @var Mangan
	 */
	private $_mangan = null;

	/**
	 * Values of this instance
	 * @var mixed[]
	 */
	private $_values = [];
	private $_defaults = [];

	public function __construct($model, ?Mangan $mangan = null)
	{
		// This is to use get/set
		foreach ($this->_getOptionNames() as $name)
		{
			PropertyMaker::defineProperty($this, $name, $this->_defaults);
		}

		foreach (ManganMeta::create($model)->type()->clientFlags as $name => $value)
		{
			$this->_values[$name] = $value;
		}
		$this->_mangan = $mangan ?? Mangan::fromModel($model);
	}

	public function __get($name)
	{
		if (array_key_exists($name, $this->_values))
		{
			return $this->_values[$name]; // We have flag set, return it
		}
		return $this->_mangan->$name;
	}

	public function __set($name, $value)
	{
		$this->_values[$name] = $value;
	}

	public function __unset($name)
	{
		unset($this->_values[$name]);
	}

	public function __isset($name)
	{
		return isset($this->_values[$name]);
	}

	public function getSaveOptions($extraOptions = [])
	{
        $session = Transaction::getRunningSession($this->_mangan);
        if ($session !== null) {
            // Transaction write concern belongs to startTransaction(), not each write.
            return array_replace(['session' => $session], $extraOptions);
        }
        $result = [];
        $overrides = array_filter([
            'w' => $this->w,
            'wtimeout' => $this->wTimeoutMS,
            'j' => $this->journal,
        ], static fn($value): bool => $value !== null);
        if ($overrides !== []) {
            $inherited = (array)$this->_mangan->getManager()->getWriteConcern()->bsonSerialize();
            // Restore a merged exported concern so an unspecified w remains
            // unspecified when only journaling or the timeout is overridden.
            $result['writeConcern'] = WriteConcern::__set_state(array_replace($inherited, $overrides));
        }
        return array_replace($result, $extraOptions);
	}

}
