<?php

use Maslosoft\Ilmatar\Components\Controller;
use Maslosoft\Ilmatar\Widgets\Form\ActiveForm;
use Maslosoft\Mangan\Annotations\DbRefArrayAnnotation;
use Maslosoft\Zamm\DocBlock;
?>
<?php

/* @var $this Controller */
/* @var $form ActiveForm */
$doc = new DocBlock(DbRefArrayAnnotation::class);
?>
<title>Db Ref Array</title>

#Db Ref Array Annotation

<?= $doc; ?>