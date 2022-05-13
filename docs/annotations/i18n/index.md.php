<?php

use Maslosoft\Ilmatar\Components\Controller;
use Maslosoft\Ilmatar\Widgets\Form\ActiveForm;
use Maslosoft\Mangan\Annotations\I18NAnnotation;
use Maslosoft\Zamm\DocBlock;
?>
<?php

/* @var $this Controller */
/* @var $form ActiveForm */
$doc = new DocBlock(I18NAnnotation::class);
?>
<title>I18N</title>

#I18N (International) Annotation

<?= $doc; ?>