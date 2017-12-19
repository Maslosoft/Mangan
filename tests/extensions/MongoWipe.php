<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Maslosoft\ManganTest\Extensions;

use Codeception\Event\TestEvent;
use Codeception\Extension;
use Exception;
use Maslosoft\Mangan\Exceptions\ManganException;
use Maslosoft\Mangan\Interfaces\Exception\ExceptionCodeInterface;
use Maslosoft\Mangan\Mangan;

/**
 * MongoWipe
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class MongoWipe extends Extension
{

	// list events to listen to
	public static $events = [
		'test.before' => 'testBefore',
	];

	public function testBefore(TestEvent $e)
	{
		if(!defined('MANGAN_TEST_ENV'))
		{
			throw new Exception('This extension requires test environment. Constant `MANGAN_TEST_ENV` needs to be true.');
		}
		if(!MANGAN_TEST_ENV)
		{
			throw new Exception('This extension requires test environment. Constant `MANGAN_TEST_ENV` needs to be true.');
		}
		if (isset($this->config['connectionIds']))
		{
			foreach ((array) $this->config['connectionIds'] as $connectionId)
			{
				try
				{
					(new Mangan($connectionId))->dropDb();
				}
				catch (ManganException $e)
				{
					// Skip if not existing db is about to be dropped
					if ($e->getCode() !== ExceptionCodeInterface::CouldNotSelect)
					{
						throw $e;
					}
				}
			}
		}
		else
		{
			try
			{
				(new Mangan())->dropDb();
			}
			catch (ManganException $e)
			{
				// Skip if not existing db is about to be dropped
				if ($e->getCode() !== ExceptionCodeInterface::CouldNotSelect)
				{
					throw $e;
				}
			}
		}
	}

}
