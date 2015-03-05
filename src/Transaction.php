<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Maslosoft\Mangan;

use Maslosoft\Addendum\Interfaces\IAnnotated;
use Maslosoft\Mangan\Exceptions\TransactionException;
use Maslosoft\Mangan\Helpers\CommandProxy;

/**
 * Transaction
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class Transaction
{

	const IsolationMVCC = 'mvcc';
	const IsolationSerializable = 'serializable';
	const IsolationReadUncommitted = 'readUncommitted';
	const CommandBegin = 'beginTransaction';
	const CommandCommit = 'commitTransaction';
	const CommandRollback = 'rollbackTransaction';

	/**
	 *
	 * @var CommandProxy
	 */
	private $cmd = null;

	/**
	 * Whenever transaction is currently active
	 * @var bool
	 */
	private static $isActive = false;

	/**
	 * Whenever transactions are available in current database
	 * @var bool
	 */
	private static $isAvailable = true;

	/**
	 * Begin new transaction
	 * @param IAnnotated $model
	 * @param enum $isolation
	 */
	public function __construct(IAnnotated $model, $isolation = self::IsolationMVCC)
	{
		if (!self::$isAvailable)
		{
			return;
		}
		if (self::$isActive)
		{
			throw new TransactionException('Transaction is already running');
		}

		$this->cmd = new CommandProxy($model);
		$this->cmd->call(self::CommandBegin, [
			'isolation' => $isolation
		]);

		self::$isActive = true;
	}

	public function isAvailable()
	{
		return $this->cmd->isAvailable(self::CommandBegin);
	}

	public function commit()
	{
		$this->cmd->call(self::CommandCommit);
	}

	public function rollback()
	{
		$this->cmd->call(self::CommandRollback);
	}

}
