<?php
	$historyfile = $config->jsonfilepath.session_id()."-cipayment.json";
	//$historyfile = $config->jsonfilepath."cioi-cipayment.json";

	if (checkformatterifexists($user->loginid, 'ci-payment-history', false)) {
		$defaultjson = json_decode(getformatter($user->loginid, 'ci-payment-history', false), true);
	} else {
		$default = $config->paths->content."cust-information/screen-formatters/default/ci-payment-history.json";
		$defaultjson = json_decode(file_get_contents($default), true);
	}

	$columns = array_keys($defaultjson['detail']['columns']);
	$fieldsjson = json_decode(file_get_contents($config->companyfiles."json/cipyfmattbl.json"), true);

	$table = array('maxcolumns' => $defaultjson['cols'], 'detail' => array('maxrows' => $defaultjson['detail']['rows'], 'rows' => array()), );
	for ($i = 1; $i < $defaultjson['detail']['rows'] + 1; $i++) {
		$table['detail']['rows'][$i] = array('columns' => array());
		$count = 1;
		foreach($columns as $column) {
			if ($defaultjson['detail']['columns'][$column]['line'] == $i) {
				$table['detail']['rows'][$i]['columns'][$defaultjson['detail']['columns'][$column]['column']] = array('id' => $column, 'label' => $defaultjson['detail']['columns'][$column]['label'], 'column' => $defaultjson['detail']['columns'][$column]['column'], 'col-length' => $defaultjson['detail']['columns'][$column]['col-length'], 'before-decimal' => $defaultjson['detail']['columns'][$column]['before-decimal'], 'after-decimal' => $defaultjson['detail']['columns'][$column]['after-decimal'], 'date-format' => $defaultjson['detail']['columns'][$column]['date-format']);
				$count++;
			}
		}
	}

	if ($config->ajax) {
		echo '<p>' . makeprintlink($config->filename, 'View Printable Version') . '</p>';
	}
?>
<?php if (file_exists($historyfile)) : ?>
	<?php $historyjson = json_decode(file_get_contents($historyfile), true);  ?>
	<?php if (!$historyjson) { $historyjson= array('error' => true, 'errormsg' => 'The Payment History JSON contains errors');} ?>

	<?php if ($historyjson['error']) : ?>
		<div class="alert alert-warning" role="alert"><?php echo $historyjson['errormsg']; ?></div>
	<?php else : ?>
		<?php include $config->paths->content."/cust-information/tables/payment-history-formatted.php"; ?>
   		<script>
			$(function() {
				$('#payments').DataTable();
			})
		</script>
	<?php endif; ?>
<?php else : ?>
	<div class="alert alert-warning" role="alert">Information Not Available</div>
<?php endif; ?>
