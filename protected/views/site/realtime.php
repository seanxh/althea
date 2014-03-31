altheaChart({
	'date' : '<?php echo $date?>',
	'data': <?php echo empty($data) ?  '{}' : json_encode($data); ?>
})