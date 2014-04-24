<?php
Yii::import('application.components.mongo.models.*', true);
class MongoCodeModel extends CCodeModel
{
	public $tablePrefix;
	public $tableName;
	public $modelClass;
	public $modelPath = 'application.models';
	public $baseClass = 'MongoRecord';
	public $baseClassDir = 'base';
	public $mergeBaseClasses = false;
	protected $_modelPaths = [];

	protected $_tagPaths = ['application.components.mongo.models'];

	protected $_classTags = [];
	protected $_fieldTags = [];

	public function rules()
	{
		return array_merge(parent::rules(), [
			 ['baseClass, modelPath', 'filter', 'filter' => 'trim'],
			 ['modelPath, baseClass', 'required'],
			 ['tableName, modelPath', 'match', 'pattern' => '/^(\w+[\w\.\s]*|\*?|\w+\.\*)$/', 'message' => '{attribute} should only contain word characters, dots, and an optional ending asterisk.'],
			 ['modelClass, baseClass', 'match', 'pattern' => '/^[a-zA-Z_]\w*$/', 'message' => '{attribute} should only contain word characters.'],
			 ['modelPath', 'validateModelPaths', 'skipOnError' => true],
			 ['baseClass, modelClass', 'validateReservedWord', 'skipOnError' => true],
			 ['baseClass', 'validateBaseClass', 'skipOnError' => true],
			 ['modelPath, baseClass', 'sticky'],
		]);
	}

	public function attributeLabels()
	{
		return array_merge(parent::attributeLabels(), [
			 'modelPath' => 'Model Paths',
			 'baseClass' => 'Base Class',
		]);
	}

	public function requiredTemplates()
	{
		return [
			 'model.php',
		];
	}

	public function init()
	{
		var_dump(CJSON::decode('{"name":"John", "surname":"Smith"}', false));
		parent::init();
	}

	/**
	 * TODO Prepare
	 * @return type
	 */
	public function prepare()
	{
		foreach(preg_split('~\s+~', $this->modelPath) as $path)
		{
			$this->_modelPaths[$path] = Yii::getPathOfAlias($path);
		}

		$this->getTags();

		foreach($this->_modelPaths as $alias => $path)
		{
			$dir = new DirectoryIterator($path);
			foreach($dir as $file)
			{
				$name = $file->getFilename();
				if($file->isFile() && preg_match('~\.php$~', $name) && !preg_match('~.+_Base$~', $name))
				{
					$className = preg_replace('~\.php$~', '', $name);
					$classPath = "$alias.$className";
					Yii::import($classPath, true);
					$class = new ReflectionClass($className);
					if($class->isSubclassOf($this->baseClass))
					{
						$this->prepareModel($alias, $className);
					}
				}
			}
		}

		var_dump($this->_modelPaths);
		return;
	}

	public function prepareModel($alias, $className)
	{
		$class = new ReflectionClass($className);
		$this->extractTags($class->getDocComment());
		foreach($class->getProperties() as $prop)
		{
			echo get_class($prop);
		}
	}

	protected function extractTags($comment)
	{
		$matches = [];
		$tags = [];
		preg_match_all('~@(\w+)\s+(.+)~', $comment, $matches);
		foreach($matches[1] as $i => $name)
		{
			$className = ucfirst($name) . 'Attr';
			echo $className;
			if(class_exists($className))
			{

				$tags[] = [$name => $matches[2][$i]];
			}
		}
		var_dump($tags);
	}

	public function validateModelPaths($attribute, $params)
	{
		foreach(preg_split('~\s+~', $this->modelPath) as $path)
		{
			$dir = Yii::getPathOfAlias($path);
			if($dir === false)
			{
				$this->addError('modelPath', sprintf('Model path "%s" must be a valid path alias.', $path));
			}
			else
			{
				if(false == is_dir($dir))
				{
					$this->addError('modelPath', sprintf('Model path "%s" points to non existent directory "%s".', $path, $dir));
				}
			}
		}
	}

	public function validateBaseClass($attribute, $params)
	{
		$class = @Yii::import($this->baseClass, true);
		if(!is_string($class) || !$this->classExists($class))
		{
			$this->addError('baseClass', "Class '{$this->baseClass}' does not exist or has syntax error.");
		}
		elseif($class !== 'MongoRecord' && !is_subclass_of($class, 'MongoRecord'))
		{
			$this->addError('baseClass', "'{$this->model}' must extend from MongoRecord.");
		}
	}

	public function generateLabels($table)
	{
		$labels = [];
		foreach($table->columns as $column)
		{
			if($column->label)
			{
				$labels[$column->name] = $column->label;
			}
			else
			{
				$label = ucwords(trim(strtolower(str_replace(['-', '_'], ' ', preg_replace('/(?<![A-Z])[A-Z]/', ' \0', $column->name)))));
				$label = preg_replace('/\s+/', ' ', $label);
				if(strcasecmp(substr($label, -3), ' id') === 0)
					$label = substr($label, 0, -3);
				if($label === 'Id')
					$label = 'ID';
				$labels[$column->name] = $label;
			}
		}
		return $labels;
	}

	public function generateRules($table)
	{
		$rules = [];
		$required = [];
		$integers = [];
		$numerical = [];
		$length = [];
		$safe = [];
		foreach($table->columns as $column)
		{
			if($column->isPrimaryKey && $table->sequenceName !== null)
				continue;
			$r = !$column->allowNull && $column->defaultValue === null;
			if($r)
				$required[] = $column->name;
			if($column->type === 'integer')
				$integers[] = $column->name;
			else if($column->type === 'double')
				$numerical[] = $column->name;
			else if($column->type === 'string' && $column->size > 0)
				$length[$column->size][] = $column->name;
			else if(!$column->isPrimaryKey && !$r)
				$safe[] = $column->name;
		}
		if($required !== [])
			$rules[] = "array('" . implode(', ', $required) . "', 'required')";
		if($integers !== [])
			$rules[] = "array('" . implode(', ', $integers) . "', 'numerical', 'integerOnly'=>true)";
		if($numerical !== [])
			$rules[] = "array('" . implode(', ', $numerical) . "', 'numerical')";
		if($length !== [])
		{
			foreach($length as $len => $cols)
				$rules[] = "array('" . implode(', ', $cols) . "', 'length', 'max'=>$len)";
		}
		if($safe !== [])
			$rules[] = "array('" . implode(', ', $safe) . "', 'safe')";

		return $rules;
	}
}