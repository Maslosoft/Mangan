<?php

/**
 * This software package is licensed under AGPL or Commercial license.
 *
 * @package maslosoft/mangan
 * @licence AGPL or Commercial
 * @copyright Copyright (c) Piotr Masełkowski <pmaselkowski@gmail.com>
 * @copyright Copyright (c) Maslosoft
 * @copyright Copyright (c) Others as mentioned in code
 * @link http://maslosoft.com/mangan/
 */

namespace Maslosoft\Mangan\Meta;

/**
 * ValidatorMeta
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class ValidatorMeta extends BaseMeta
{

	/**
	 * Proxy class. This will use Mangan::validators configuration to init `$class` field.
	 * NOTE: This is reserved and *cannot* be used as validator field.
	 * @var string
	 */
	public $proxy = '';

	/**
	 * Validator class. If empty, will try to get from proxy class.
	 * This has priority over `proxy` field.
	 * @var string
	 */
	public $class = '';

	/**
	 * @var string the user-defined error message. Different validators may define various
	 * placeholders in the message that are to be replaced with actual values. All validators
	 * recognize "{attribute}" placeholder, which will be replaced with the label of the attribute.
	 */
	public $message;

	/**
	 * @var boolean whether this validation rule should be skipped when there is already a validation
	 * error for the current attribute. Defaults to false.
	 * @since 1.1.1
	 */
	public $skipOnError = false;

	/**
	 * @var array list of scenarios that the validator should be applied.
	 * Each array value refers to a scenario name with the same name as its array key.
	 */
	public $on;

	/**
	 * @var boolean whether attributes listed with this validator should be considered safe for massive assignment.
	 * Defaults to true.
	 * @since 1.1.4
	 */
	public $safe = true;

	/**
	 * @var array list of scenarios that the validator should not be applied to.
	 * Each array value refers to a scenario name with the same name as its array key.
	 * @since 1.1.11
	 */
	public $except = NULL;

	// and more...
}
