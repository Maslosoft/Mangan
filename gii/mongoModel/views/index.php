<?php
$class=get_class($model);
?>
<h1>Mongo Generator</h1>

<p>This generator generates a base model class from concrete model class meta data.</p>

<?php $form=$this->beginWidget('CCodeForm', array('model'=>$model)); ?>
	<div class="row sticky">
		<?php echo $form->labelEx($model,'baseClass'); ?>
		<?php echo $form->textField($model,'baseClass',array('size'=>65)); ?>
		<div class="tooltip">
			This is the class that the new model class will extend from.
			Please make sure the class exists and can be autoloaded.
		</div>
		<?php echo $form->error($model,'baseClass'); ?>
	</div>
	<div class="row sticky">
		<?php echo $form->labelEx($model,'modelPath'); ?>
		<?php echo $form->textArea($model,'modelPath', array('style'=>'width:413px')); ?>
		<div class="tooltip">
			This refers to the directories which contains models.
			It should be specified in the form of a path alias, for example, <code>application.models</code> whitespace separated values.
		</div>
		<?php echo $form->error($model,'modelPath'); ?>
	</div>

<?php $this->endWidget(); ?>
