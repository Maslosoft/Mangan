<?php

use Maslosoft\Mangan\Annotations\EmbeddedAnnotation;
use Maslosoft\Zamm\DocBlock;
?>
<?php
$doc = new DocBlock(EmbeddedAnnotation::class);
?>
<title>Embedded</title>
#Embedded annotation

<?= $doc; ?>