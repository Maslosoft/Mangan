<?php

use Maslosoft\Ilmatar\Components\Controller;
use Maslosoft\Ilmatar\Widgets\Form\ActiveForm;
use Maslosoft\Mangan\Traits\I18NAbleTrait;
use Maslosoft\Zamm\DocBlock;
use Maslosoft\Zamm\Iterator;
use Maslosoft\Zamm\ShortNamer;
?>
<?php

/* @var $this Controller */
/* @var $form ActiveForm */
?>

<title>Traits</title>
# Traits

Mangan comes with various trait's for easier implementation of common cases

<?php

foreach (Iterator::ns(I18NAbleTrait::class) as $class):
	$header = '### ' . (new ShortNamer($class)) . PHP_EOL;
	echo $header;
	echo new DocBlock($class);
endforeach;
?>
