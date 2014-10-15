<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Maslosoft\Mangan\Traits\Defaults;

/**
 * MongoClientOptions
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
trait MongoClientOptions
{

	/**
	 * Boolean, defaults to FALSE. If journaling is enabled, it works exactly like "j".
	 * If journaling is not enabled, the write operation blocks until it is synced to database files on disk.
	 * If TRUE, an acknowledged insert is implied and this option will override setting "w" to 0.
	 *
	 * Note: If journaling is enabled, users are strongly encouraged to use the "j" option instead of "fsync".
	 * Do not use "fsync" and "j" simultaneously, as that will result in an error.
	 *
	 * @var bool
	 */
	public $fsync = false;

	/**
	 * Boolean, defaults to FALSE. If journaling is enabled, it works exactly like "j".
	 * If journaling is not enabled, the write operation blocks until it is synced to database files on disk.
	 * If TRUE, an acknowledged insert is implied and this option will override setting "w" to 0.
	 *
	 * Note: If journaling is enabled, users are strongly encouraged to use the "j" option instead of "fsync".
	 * Do not use "fsync" and "j" simultaneously, as that will result in an error.
	 * @var bool
	 */
	public $j = false;

	/**
	 * Boolean, defaults to FALSE. Forces the write operation to block until it is synced to the journal on disk.
	 * If TRUE, an acknowledged write is implied and this option will override setting "w" to 0.
	 *
	 * Note: If this option is used and journaling is disabled, MongoDB 2.6+ will raise an error and the write will fail; older server versions will simply ignore the option.
	 * @var int
	 */
	public $socketTimeoutMS = 30000;

	/**
	 * See Write Concerns. The default value for MongoClient is 1.
	 * @var mixed
	 */
	public $w = 0;

	/**
	 * This option specifies the time limit, in milliseconds, for write concern acknowledgement.
	 * It is only applicable when "w" is greater than 1, as the timeout pertains to replication.
	 * If the write concern is not satisfied within the time limit, a MongoCursorException will be thrown.
	 * A value of 0 may be specified to block indefinitely. The default value for MongoClient is 10000 (ten seconds).
	 * @var int
	 */
	public $wTimeoutMS = 10000;

	protected function _getOptionNames()
	{
		return ['fsync', 'j', 'socketTimeoutMS', 'w', 'wTimeoutMS'];
	}
}
