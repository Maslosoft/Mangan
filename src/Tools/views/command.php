	/**
<?php foreach($help as $line):?>
	 * <?= $line . "\n";?>
<?php endforeach;?>
	 */
	public function <?= $name;?>()
	{
		return $this->call('<?= $mongoName;?>', func_get_args());
	}
