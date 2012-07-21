<?php
/**
 * CUniqueValidator class file.
 *
 * @author Ianaré Sévi
 * @author Florian Fackler <florian.fackler@mintao.com>
 * @link http://mintao.com
 * @copyright Copyright (c) 2008-2010 Yii Software LLC
 * @license New BSD license
 */

/**
 * CUniqueValidator validates that the attribute value is unique in the corresponding database table.
 *
 * @author Florian Fackler <florian.fackler@mintao.com>
 * @version $Id$
 * @package system.validators
 * @since 1.0
 */
class CMongoUniqueValidator extends CValidator
{
	/**
	 * @var boolean whether the attribute value can be null or empty. Defaults to true,
	 * meaning that if the attribute is empty, it is considered valid.
	 */
	public $allowEmpty=true;
	/**
	 * @var string the ActiveRecord class name that should be used to
	 * look for the attribute value being validated. Defaults to null, meaning using
	 * the class of the object currently being validated.
	 * You may use path alias to reference a class name here.
	 * @see attributeName
	 * @since 1.0.8
	 */
	public $className;
	/**
	 * @var string the ActiveRecord class attribute name that should be
	 * used to look for the attribute value being validated. Defaults to null,
	 * meaning using the name of the attribute being validated.
	 * @see className
	 * @since 1.0.8
	 */
	public $attributeName;
	/**
	 * @var array additional query criteria. This will be combined with the condition
	 * that checks if the attribute value exists in the corresponding table column.
	 * This array will be used to instantiate a {@link CDbCriteria} object.
	 * @since 1.0.8
	 */
	public $criteria=array();
	/**
	 * @var string the user-defined error message. The placeholders "{attribute}" and "{value}"
	 * are recognized, which will be replaced with the actual attribute name and value, respectively.
	 */
	public $message;
	/**
	 * @var boolean whether this validation rule should be skipped if when there is already a validation
	 * error for the current attribute. Defaults to true.
	 * @since 1.1.1
	 */
	public $skipOnError=true;

	/**
	 * Validates the attribute of the object.
	 * If there is any error, the error message is added to the object.
	 * @param CModel $object the object being validated
	 * @param string $attribute the attribute being validated
	 */
	protected function validateAttribute($object,$attribute)
	{
		$value=$object->$attribute;
		if($this->allowEmpty && $this->isEmpty($value))
			return;

		$className=$this->className===null?get_class($object):Yii::import($this->className);
		$attributeName=$this->attributeName===null?$attribute:$this->attributeName;
		$finder=EMongoDocument::model($className);
		$criteria=new EMongoCriteria;
		$criteria->{$attribute}=$value;
		if($this->criteria!==array())
			$criteria->mergeWith($this->criteria);

		if(!$object instanceof EMongoDocument || $object->isNewRecord)
			$exists=$finder->exists($criteria);
		else
		{
			$criteria->limit=2;
			$objects=$finder->findAll($criteria);
			$n=count($objects);
			if($n===1)
			{
				if($column->isPrimaryKey)  // primary key is modified and not unique
					$exists=$object->getOldPrimaryKey()!=$object->getPrimaryKey();
				else // non-primary key, need to exclude the current record based on PK
					$exists=$objects[0]->getPrimaryKey()!=$object->getOldPrimaryKey();
			}
			else
				$exists=$n>1;
		}

		if($exists)
		{
			$message=$this->message!==null?$this->message:Yii::t('yii','{attribute} "{value}" has already been taken.');
			$this->addError($object,$attribute,$message,array('{value}'=>$value));
		}
	}
}
