<?php

abstract class BasicTestModel extends EMongoDocument
{
	public function getCollectionName()
	{
		return 'testCollection';
	}

	public function getInternalVariableByName($name)
	{
		return $this->$name;
	}
}