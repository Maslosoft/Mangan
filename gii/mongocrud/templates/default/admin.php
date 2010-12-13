<?php
/**
 * The following variables are available in this template:
 * - $this: the CrudCode object
 */
?>
<?php
echo "<?php\n";
$label=$this->pluralize($this->class2name($this->modelClass));
echo "\$this->breadcrumbs=array(
	'$label'=>array('index'),
	'Manage',
);\n";
?>

$this->menu=array(
	array('label'=>'List <?php echo $this->modelClass; ?>', 'url'=>array('index')),
	array('label'=>'Create <?php echo $this->modelClass; ?>', 'url'=>array('create')),
);
?>

<h1>Manage <?php echo $this->pluralize($this->class2name($this->modelClass)); ?></h1>

<?php echo "<?php"; ?> $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'<?php echo $this->class2id($this->modelClass); ?>-grid',
	'dataProvider'=>new EMongoDocumentDataProvider('<?php echo $this->modelClass; ?>', array(
		'sort'=>array(
			'attributes'=>array(
<?php
$count=0;
foreach($this->modelObject->attributeNames() as $name)
{
	if(++$count==7)
		echo "\t\t\t\t/*\n";
	echo "\t\t\t\t'".$name."',\n";
}
if($count>=7)
	echo "\t\t\t\t*/\n";
?>
			),
		),
	)),
	'columns'=>array(
<?php
$count=0;
foreach($this->modelObject->attributeNames() as $name)
{
	if(++$count==7)
		echo "\t\t/*\n";
	echo "\t\t'".$name."',\n";
}
if($count>=7)
	echo "\t\t*/\n";
?>
		array(
			'class'=>'CButtonColumn',
		),
	),
)); ?>