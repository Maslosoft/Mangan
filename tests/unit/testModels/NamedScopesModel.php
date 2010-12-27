<?php

require_once dirname(__FILE__).'/BasicOperationsModel.php';

class NamedScopesModel extends BasicOperationsModel
{
	public function paramScope($param)
	{
		$criteria = $this->getDbCriteria();
		$criteria->field1 = $param;
		$this->setDbCriteria($criteria);

		return $this;
	}

	public function scopes()
	{
		return array(
			'scope'=>array(
				'conditions'=>array(
					'field2' => array('<=' => 2)
				),
			),
		);
	}

	public function defaultScope()
	{
		return array(
			'conditions'=>array(
				'field1' => array('>' => 1),
			),
		);
	}
}