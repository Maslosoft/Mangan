<ul>
<? foreach($tables as $table):?>
	<li>
		<?echo $table->name?>
		<?if($table->columns):?>
		<ul>
			<?foreach($table->columns as $column):?>
			<li><?echo $column->name?></li>
			<?endforeach;?>
		</ul>
		<?endif;?>
	</li>
<?endforeach;?>;
</ul>
