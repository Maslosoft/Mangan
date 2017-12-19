<?php

/**
 * This SOFTWARE PRODUCT is protected by copyright laws and international copyright treaties,
 * as well as other intellectual property laws and treaties.
 * This SOFTWARE PRODUCT is licensed, not sold.
 * For full licence agreement see enclosed LICENCE.html file.
 *
 * @licence LICENCE.html
 * @copyright Copyright (c) Piotr Masełkowski <pmaselkowski@gmail.com>
 * @copyright Copyright (c) Maslosoft
 * @link http://maslosoft.com/
 */

namespace Maslosoft\ManganTest\Models\Debug;

use Maslosoft\Mangan\Document;

/**
 * Description of UserGroup
 * @author Piotr
 * @Label('User group')
 * @method UserGroup model() Do not call it dynamically, it's only for IDE, use ::model() instead.
 */
class UserGroup extends Document
{

	/**
	 * This is group for public access, no authorization required for this group
	 */
	const CodePublic = 'public';

	/**
	 * This is group for registered users
	 */
	const CodeRegistered = 'registered';

	/**
	 * Group with this code is allowed to access anything
	 */
	const CodeAdmin = 'admin';

	/**
	 * Group name
	 *
	 * @Label('Name')
	 * @Description('User friendly group name')
	 * @RequiredValidator
	 * @Decorator({'Link', 'uac/userGroup/update', 'id'})
	 * @I18N(allowAny = true)
	 * @var string
	 */
	public $name = '';

	/**
	 * Group name
	 *
	 * @Label('Description')
	 * @Description('Put any description usefull for other users, or for your future reference')
	 * @SafeValidator
	 * @Decorator({'Link', 'uac/userGroup/update', 'id'})
	 * @I18N(allowAny = true)
	 * @FormRenderer('TextArea')
	 * @var string
	 */
	public $description = '';

	/**
	 * @Label('Code')
	 * @Description('This code can be used to check user membership in templates')
	 * @UniqueValidator
	 * @Decorator({'Link', 'uac/userGroup/update', 'id'})
	 * @RequiredValidator
	 * @var string
	 */
	public $code = '';

	/**
	 * If immutable, this group cannot be deleted, and cannot have code changed
	 * This is for build-in groups, like admin, public, registered
	 * @see UserGroup::getImmutable()
	 * @Readonly
	 * @Persistent(false)
	 * @var bool
	 */
	public $immutable = false;

	/**
	 * User roles for this group
	 * @SafeValidator
	 * @var bool[][][]
	 */
	public $roles = null;

	/**
	 * Cached group codes, used for permissions
	 * @var bool[]
	 */
	private static $_groupCodes = null;

	/**
	 * Cached groups roles, used for permissions
	 * @var int[][]
	 */
	private static $_groupRoles = null;

	public function __construct($scenario = 'insert', $lang = '')
	{
		parent::__construct($scenario, $lang);

		// This is not nessary, but should have not side effect
		$this->setLang($lang);
	}

	public function getLanguages()
	{
		return ['en', 'pl'];
	}

	public function getDefaultLanguage()
	{
		return 'pl';
	}

	public function getImmutable()
	{
		switch ($this->getAttribute('code'))
		{
			case self::CodePublic:
			case self::CodeRegistered:
			case self::CodeAdmin:
				return true;
		}
		return false;
	}

	public function getCode()
	{
		return $this->getAttribute('code');
	}

	public function setCode($value)
	{
		if (!$this->getImmutable())
		{
			$this->setAttribute('code', $value);
		}
		return $this;
	}

	public static function findGroupCodes()
	{
		if (null === self::$_groupCodes)
		{
			foreach (UserGroup::model()->findAll() as $group)
			{
				self::$_groupCodes[$group->code] = false;
			}
		}
		return self::$_groupCodes;
	}

	public static function findGroupRoles()
	{
		if (null === self::$_groupRoles)
		{
			foreach (UserGroup::model()->findAll() as $group)
			{
				self::$_groupRoles[$group->code] = $group->roles;
			}
			if(!self::$_groupRoles)
			{
				//throw new RuntimeException(tx('There are no user groups, have you installed application?'));
			}
		}
		return self::$_groupRoles;
	}

	/**
	 * Find group by code
	 * @param string $code
	 * @return UserGroup
	 */
	public function findByCode($code)
	{
		return $this->findByAttributes(['code' => $code]);
	}

}
