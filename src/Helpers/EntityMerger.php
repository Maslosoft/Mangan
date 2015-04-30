<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Maslosoft\Mangan\Helpers;

/**
 * Helper for mergind arrays of documents with it's raw data.
 * TODO Implement it
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class EntityMerger
{
/**
	 * TODO: Something like this
	 * @param IAnnotated[] $instances
	 * @param mixed[] $dbValues
	 * @param mixed[] $data
	 * @return IAnnotated|null
	 */
	private function _getInstance($instances, $dbValues, $data)
	{
		if(!count($instances))
		{
			return null;
		}
		$map = [];
		foreach($dbValues as $val)
		{
			$id = (string)$val['_id'];
			$map[$id] = true;
		}
		foreach($instances as $instance)
		{
			$id = (string)$instance->_id;
			if(isset($map[$id]) && $data['_id'] == $id && $instance instanceof $data['_class'])
			{
				return $instance;
			}
		}
		return null;
	}
}
