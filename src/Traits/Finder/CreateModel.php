<?php


namespace Maslosoft\Mangan\Traits\Finder;


use Maslosoft\Addendum\Interfaces\AnnotatedInterface;
use Maslosoft\Mangan\Exceptions\ManganException;
use Maslosoft\Mangan\Meta\ManganMeta;
use Maslosoft\Mangan\Transformers\RawArray;

trait CreateModel
{
	/**
	 * Create model from `$data`.
	 *
	 * NOTE: It requires `getModel` method accessible
	 * within this method.
	 *
	 * NOTE: Do not declare abstract `getModel` here
	 * as *possibly* it might be protected or public in classes
	 * using this trait.
	 *
	 * @param                    $data
	 * @return AnnotatedInterface
	 * @throws ManganException
	 */
	protected function createModel($data)
	{
		if (!empty($data['$err']))
		{
			throw new ManganException(sprintf("There is an error in query: %s", $data['$err']));
		}

		// By default create instances of same
		// type as provided model
		$model = $this->getModel();

		// For non homogeneous collections class
		// need to be taken from data, not defined
		// by model
		if(ManganMeta::create($model)->type()->homogenous === false)
		{
			$model = null;
		}
		return RawArray::toModel($data, $model);
	}
}