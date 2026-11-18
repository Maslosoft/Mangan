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
use Maslosoft\Mangan\Helpers\RuntimeState;
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
	private Session $session;


	/**
	 * Begin new transaction. The `$options` parameter is passed to Session::startTransaction().
	 * @link https://www.php.net/manual/en/mongodb-driver-session.starttransaction.php
	 * @see  Session
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
            if (is_string($m) && is_a($m, AnnotatedInterface::class, true)) {
                $m = new $m();
            }
            if (!$m instanceof AnnotatedInterface) {
                throw new UnexpectedValueException('Each transaction model must implement AnnotatedInterface.');
            }
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
		$this->session = $client->startSession($mangan->sessionOptions);
		$this->session->startTransaction($options);
		RuntimeState::current()->sessions[] = $this->session;
		RuntimeState::current()->sessionConnections[spl_object_id($this->session)] = $client;
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
	public static function getRunningSession(?Mangan $mangan = null): ?Session
    {
        $state = RuntimeState::current();
        foreach (array_reverse($state->sessions) as $session) {
            if ($mangan === null || $state->sessionConnections[spl_object_id($session)] === $mangan->getConnection()) {
                return $session;
            }
        }
        return null;
    }

	public static function isRunning(?Mangan $mangan = null): bool
	{
		return self::getRunningSession($mangan) !== null;
	}

	private function finish(): void
	{
		foreach (RuntimeState::current()->sessions as $key => $session)
        {
            if ($session === $this->session)
            {
                unset(RuntimeState::current()->sessions[$key]);
                unset(RuntimeState::current()->sessionConnections[spl_object_id($session)]);
                break;
            }
        }
	}
}
