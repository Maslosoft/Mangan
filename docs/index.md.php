<?php

use Maslosoft\Ilmatar\Widgets\Form\ActiveForm;
use Maslosoft\Ilmatar\Components\Controller;
?>
<?php
/* @var $this Controller */
/* @var $form ActiveForm */
?>

<template>docs</template>
<title>Documentation</title>

# Documentation

Mangan goal is to allow storing PHP objects in MongoDB. These can be
composed in more complex structures, be either embedding, referencing or relations.
It is generally ready to use out of a package. However at least some minimal
configuration is required, for instance database name.

### Installation

Use composer to install mangan:

```
composer require maslosoft/mangan
```

### Configuration

It is recommended to use [EmbeDi](/embedi/) to configure Mangan. This allow
separation configuration from actual usage. Minimum configuration required
is database name, using property `dbName`. Default configuration ID is `mongodb`.

*Use statements omitted*
```
$config = [
	'mongodb' => [
		'class' => Mangan::class,
		// Database name
		'dbName' => 'quick-start',
	]
];
EmbeDi::fly()->addAdapter(new ArrayAdapter($config));
```

Now, assuming that we have some models ready, Mangan is ready to do it's duty.
When using built-in base documents classes, Active Document (derived from active record)
pattern can be used, for instance:

```
$plant = new Plant;
$plant->name = 'Grass';
$plant->save();
```

Check [this repository for working example of Mangan](https://github.com/MaslosoftGuides/mangan.quick-start)