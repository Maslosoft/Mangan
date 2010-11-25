<?php

class EMongoDocument extends CAttributeCollection
{
	private $_hardSchema = false;
	public $caseSensitive = true;

	public function copyFrom($data)
	{
		if(is_array($data) || $data instanceof Traversable)
		{
			if($this->getCount() > 0)
				$this->clear();
			if($data instanceof CMap)
				$data = $data->_d;
			foreach($data as $key=>$value)
			{
				if(is_array($value))
				{
					$value = new EMongoDocument($value, $this->getReadOnly());
					$value->setBlockedSchema($this->_hardSchema);
				}
				$this->add($key, $value);
			}
		}
	}

	public function add($key, $value)
	{
		if(!$this->_hardSchema || $this->contains($key))
		{
			if(is_array($value))
				return parent::add($key, new EMongoDocument($value, $this->getReadOnly()));
			else
				return parent::add($key, $value);
		}
		else
			throw new CException(Yii::t('yii', 'Trying to add property to blocked schema!'));
	}

	public function toArray()
	{
		$arr = array();
		foreach($this as $key=>$value)
		{
			if($value instanceof EMongoDocument)
				$arr[$key] = $value->toArray();
			else
				$arr[$key] = $value;
		}
		return $arr;
	}

	public function __get($name)
	{
		if(!$this->contains($name))
			$this->$name=array();

		return parent::__get($name);
	}

	public function setBlockedSchema($value)
	{
		$this->_hardSchema = ($value == true);

		foreach($this as $value)
			if($value instanceof EMongoDocument)
				$value->setBlockedSchema($this->_hardSchema);
	}

	public function isBlockedSchema()
	{
		return $this->_hardSchema == true;
	}
}