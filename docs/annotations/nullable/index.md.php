<?php

use Maslosoft\Mangan\Annotations\NullableAnnotation;
use Maslosoft\Zamm\DocBlock;
?>
<?php
$doc = new DocBlock(NullableAnnotation::class);
?>
<title>Nullable</title>

#Nullable Annotation

<?= $doc; ?>