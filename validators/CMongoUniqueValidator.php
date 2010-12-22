<?php
/**
 * CUniqueValidator class file.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @link http://www.yiiframework.com/
 * @copyright Copyright &copy; 2008-2010 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

/**
 * CUniqueValidator validates that the attribute value is unique in the corresponding database table.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
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
    $check = $object->find(array($attribute => $value));
    if($check)
    {
      $message=$this->message!==null?$this->message:Yii::t('yii','{attribute} "{value}" has already been taken.');
      $this->addError($object,$attribute,$message);
    }
  }
}
