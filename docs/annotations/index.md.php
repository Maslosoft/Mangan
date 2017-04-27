<?php

use Maslosoft\Ilmatar\Components\Controller;
use Maslosoft\Ilmatar\Widgets\Form\ActiveForm;
use Maslosoft\Staple\Widgets\SubNavRecursive;
?>
<?php

/* @var $this Controller */
/* @var $form ActiveForm */
?>
<title>4. Annotations</title>
#Annotations

Annotations are used to define model behaviors and to add extra parameters
used in end-user application.

### Available annotations:

<?php

echo new SubNavRecursive([
'root' => __DIR__,
 'path' => '.',
 'skipLevel' => 1,
]);
?>