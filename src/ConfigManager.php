<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Maslosoft\Mangan;

/**
 * Config Manager can be used to customize configuration of mangan without copying
 * whole configuration file. This allows to merge user defined parts with original
 * configuration. Proper configuration of sanitizers, decorators, filters is
 * crucial for proper mangan operation.
 * 
 * Example of recommended usage:
 * ```php
 * $config = array_replace_recursive(ConfigManager::getDefault(), [
 * 	'filters' => [
 * 		RawArray::class => [
 * 			MyCustomFilter::class,
 * 		],
 * 	],
 * 	'sanitizersMap' => [
 * 		RawArray::class => [
 * 			StringSanitizer::class => HtmlSanitizer::class
 * 		]
 * 	]
 * ]);
 * ```
 *
 * Above example snippet will add MyCustomFilter class to RawArray transformer
 * and remap one sanitizer also for RawArray, while keeping all other configuration
 * as it should be.
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class ConfigManager
{

	private static $config = null;

	/**
	 * Get mangan built-in configuration as array.
	 * @return array Default mangan configuration
	 */
	public static function getDefault()
	{
		if (null === self::$config)
		{
			self::$config = require __DIR__ . '/config/mangan.cfg.php';
		}
		return self::$config;
	}

}
