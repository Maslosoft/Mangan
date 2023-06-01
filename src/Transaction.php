<?php

/**
 * This software package is licensed under AGPL or Commercial license.
 *
 * @package   maslosoft/mangan
 * @licence   AGPL or Commercial
 * @copyright Copyright (c) Piotr Masełkowski <pmaselkowski@gmail.com>
 * @copyright Copyright (c) Maslosoft
 * @copyright Copyright (c) Others as mentioned in code
 * @link      https://maslosoft.com/mangan/
 */

namespace Maslosoft\Mangan;

use Maslosoft\Addendum\Interfaces\AnnotatedInterface;
use Maslosoft\Mangan\Exceptions\ManganException;
use Maslosoft\Mangan\Helpers\CollectionNamer;
use MongoDB\Client;
use MongoDB\Driver\Session;
use UnexpectedValueException;

/**
 * Transaction
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class Transaction
{
//<editor-fold defaultstate="collapsed">
	/**
	 * @deprecated
	 */
	public const IsolationMVCC = 'mvcc';
	/**
	 * @deprecated
	 */
	public const IsolationSerializable = 'serializable';
	/**
	 * @deprecated
	 */
	public const IsolationReadUncommitted = 'readUncommitted';
	/**
	 * @deprecated
	 */
	public const CommandBegin = 'beginTransaction';
	/**
	 * @deprecated
	 */
	public const CommandCommit = 'commitTransaction';
	/**
	 * @deprecated
	 */
	public const CommandRollback = 'rollbackTransaction';

//</editor-fold>
	private Client $client;

	private Session $session;

	private static $sessions = [];

	/**
	 * Begin new transaction. The `$options` parameter is same as the `Manager::startSession` method.
	 * @link https://www.php.net/manual/en/mongodb-driver-manager.startsession.php
	 * @see  Manager
	 * @param AnnotatedInterface|array|string $model
	 * @param array                           $options Transaction options
	 * @throws ManganException
	 */
	public function __construct(AnnotatedInterface|array|string $model, array $options = [])
	{
		if (is_array($model))
		{
			$models = $model;
		}
		else
		{
			$models = [$model];
		}
		if (empty($models))
		{
			throw new UnexpectedValueException('The parameter `$model` must be an array or model instance or model class name');
		}
		$connectionIds = [];
		$collectionSets = [];
		foreach ($models as $m)
		{
			$mangan = Mangan::fromModel($m);
			$connectionIds[$mangan->connectionId] = true;

			// Create collection only if not exists or exception will be thrown
			(new Finder($m))->exists();
			if(!array_key_exists($mangan->connectionId, $collectionSets))
			{
				$cmd = new Command($m);
				$collections = array_flip($cmd->listCollectionNames());
				$collectionSets[$mangan->connectionId] = $collections;
			}
			$collectionName = CollectionNamer::nameCollection($m);
			if(!array_key_exists($collectionName, $collectionSets[$mangan->connectionId]))
			{
				$cmd = new Command($m);
				$cmd->create($collectionName);
			}
		}
		assert($mangan instanceof Mangan);
		if (count($connectionIds) > 1)
		{
			throw new UnexpectedValueException("All models in transaction must have the same `connectionId`, provided connection Id's: " . implode(', ', array_keys($connectionIds)));
		}
		if (empty($options))
		{
			$options = $mangan->transactionOptions;
		}
		$client = $mangan->getConnection();
		$this->session = $client->startSession();
		$this->session->startTransaction($options);
		self::$sessions[] = $this->session;
	}

	/**
	 * @return true
	 * @deprecated Return always true
	 */
	public function isAvailable(): bool
	{
		return true;
	}

	public function commit(): void
	{
		$this->session->commitTransaction();
		$this->finish();
	}

	public function rollback(): void
	{
		$this->session->abortTransaction();
		$this->finish();
	}

	/**
	 * @return ?Session
	 * @internal Experimental
	 */
	public static function getRunningSession(): ?Session
	{
		return end(self::$sessions);
	}

	public static function isRunning(): bool
	{
		return count(self::$sessions) > 0;
	}

	private function finish(): void
	{
		array_pop(self::$sessions);
	}
}
