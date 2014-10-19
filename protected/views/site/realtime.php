altheaChart({
	'key' : '<?php echo $key?>',
	'data': <?php echo empty($data) ?  '{}' : json_encode($data); ?>
})