<?php

use Maslosoft\Ilmatar\Components\Controller;
use Maslosoft\Ilmatar\Widgets\Form\ActiveForm;
use Maslosoft\Mangan\Annotations\DbRefArrayAnnotation;
use Maslosoft\Mangan\Annotations\NullableAnnotation;
use Maslosoft\Zamm\DocBlock;
?>
<?php

/* @var $this Controller */
/* @var $form ActiveForm */
$doc = new DocBlock(NullableAnnotation::class);
?>
<title>Nullable</title>

#Nullable Annotation

<?= $doc; ?>