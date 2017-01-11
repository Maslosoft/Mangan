<?php

use Maslosoft\Ilmatar\Components\Controller;
use Maslosoft\Ilmatar\Widgets\Form\ActiveForm;
use Maslosoft\Mangan\Annotations\EmbeddedAnnotation;
use Maslosoft\Zamm\DocBlock;
?>
<?php

/* @var $this Controller */
/* @var $form ActiveForm */
$doc = new DocBlock(EmbeddedAnnotation::class);
?>
<title>Embedded</title>
#Embedded annotation

<?= $doc; ?>