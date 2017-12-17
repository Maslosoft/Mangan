<?php
/**
 * Created by PhpStorm.
 * User: peter
 * Date: 17.12.17
 * Time: 15:13
 */

namespace Maslosoft\Mangan\Helpers\Index;

use Maslosoft\Addendum\Interfaces\AnnotatedInterface;
use Maslosoft\Mangan\Command;
use Maslosoft\Mangan\Criteria\ConditionDecorator;
use Maslosoft\Mangan\Interfaces\InternationalInterface;
use Maslosoft\Mangan\Mangan;
use Maslosoft\Mangan\Meta\IndexMeta;

class IndexModel
{
	private $model = null;

	/**
	 * @var IndexMeta
	 */
	private $meta = null;

	private $indexes = [];

	public function __construct(AnnotatedInterface $model, IndexMeta $meta)
	{
		$this->model = $model;
		$this->meta = $meta;
	}

	public function apply()
	{
		$mn = Mangan::fromModel($this->model);
		$cd = new ConditionDecorator($this->model);

		$decorated = [];
		foreach ($this->meta->keys as $name => $sort)
		{
			if ($this->model instanceof InternationalInterface)
			{
				foreach ($this->model->getLanguages() as $code)
				{
					assert(!empty($code));

					// Reset cloned model languages to get only one
					// language that is currently selected. So that
					// decorated field name will have proper code.
					$cd->getModel()->setLang($code, false);
					$cd->getModel()->setLanguages([$code], false);

					$field = $cd->decorate($name);
					$key = key($field);
					$decorationKey = $code . '@' . $sort;
					if(empty($decorated[$decorationKey]))
					{
						$decorated[$decorationKey] = [];
					}
					$decorated[$decorationKey][$key] = $sort;
				}
			}
			else
			{
				$field = $cd->decorate($name);
				$key = key($field);
				$decorated[$key . '@' . $sort] = [$key => $sort];
			}
		}
		$cmd = new Command($this->model, $mn);

		$results = [];

		// Remove possible duplicated entries
		$unique = array_unique($decorated, SORT_REGULAR);
		$this->indexes = $unique;
		foreach ($unique as $keys)
		{
			$results[] = (int)$cmd->createIndex($keys, $this->meta->options);
		}
		return array_sum($results) === count($results);
	}

	public function getIndexes()
	{
		return $this->indexes;
	}
}