<?php

abstract class BasicTestEmbeddedModel extends EMongoEmbeddedDocument
{
	public function getInternalVariableByName($name)
	{
		return $this->$name;
	}
}