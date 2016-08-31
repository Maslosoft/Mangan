<?php

use Maslosoft\Ilmatar\Components\Controller;
use Maslosoft\Ilmatar\Widgets\Form\ActiveForm;
use Maslosoft\Mangan\Annotations\EmbeddedArrayAnnotation;
use Maslosoft\Zamm\DocBlock;
?>
<?php

/* @var $this Controller */
/* @var $form ActiveForm */
$doc = new DocBlock(EmbeddedArrayAnnotation::class);
?>
<title>Embedded Array</title>
#Embedded Array annotation

<?= $doc; ?>