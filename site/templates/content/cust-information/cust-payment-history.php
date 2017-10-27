<?php
	$historyfile = $config->jsonfilepath.session_id()."-cipayment.json";
	//$historyfile = $config->jsonfilepath."cioi-cipayment.json";

	if (checkformatterifexists($user->loginid, 'ci-payment-history', false)) {
		$defaultjson = json_decode(getformatter($user->loginid, 'ci-payment-history', false), true);
	} else {
		$default = $config->paths->content."cust-information/screen-formatters/default/ci-payment-history.json";
		$defaultjson = json_decode(file_get_contents($default), true);
	}
	
	if (file_exists($historyfile)) {
		// JSON file will be false if an error occurred during file_get_contents or json_decode
		$historyjson = json_decode(file_get_contents($historyfile), true);
		$historyjson = $historyjson ? $historyjson : array('error' => true, 'errormsg' => 'The Payment History JSON contains errors. JSON ERROR: '.json_last_error());
		
		if ($historyjson['error']) {
			echo $page->bootstrap->createalert('warning', $historyjson['errormsg']);
		} else {
			$table = include $config->paths->content."cust-information/screen-formatters/logic/payment-history.php";
			include $config->paths->content."cust-information/tables/payment-history-formatted.php";
			include $config->paths->content."cust-information/scripts/payment-history.js.php";
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
