<?php

use Maslosoft\Addendum\Interfaces\AnnotatedInterface;
use Maslosoft\Mangan\Interfaces\Sanitizers\Property\SanitizerInterface;
use Maslosoft\Zamm\ShortNamer;
?>
<?php
/* @var $x AnnotatedInterface */
ShortNamer::defaults()->md();
$if = new ShortNamer(SanitizerInterface::class);
?>
<title>0. Creating Sanitizer</title>

#Creating Sanitizer

