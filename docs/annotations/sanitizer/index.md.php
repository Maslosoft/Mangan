<?php

use Maslosoft\Mangan\Annotations\SanitizerAnnotation;
use Maslosoft\Zamm\DocBlock;
?>
<?php

$doc = new DocBlock(SanitizerAnnotation::class);
?>
<title>Sanitizer</title>
#Sanitizer

<?= $doc; ?>