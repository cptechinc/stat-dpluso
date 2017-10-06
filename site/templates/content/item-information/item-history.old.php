<?php
	$historyfile = $config->jsonfilepath.session_id()."-iisaleshist.json";
	//$historyfile = $config->jsonfilepath."iish-iisaleshist.json";

	if ($config->ajax) {
		echo $page->bootstrap->openandclose('p', '', $page->bootstrap->makeprintlink($config->filename, 'View Printable Version'));
	}
	
	$table = include $config->paths->content."item-information/screen-formatters/logic/item-history.php";


?>

<?php if (file_exists($historyfile)) : ?>
    <?php $historyjson = json_decode(file_get_contents($historyfile), true);  ?>
    <?php if (!$historyjson) { $historyjson = array('error' => true, 'errormsg' => 'The sales order JSON contains errors');} ?>

    <?php if ($historyjson['error']) : ?>
        <div class="alert alert-warning" role="alert"><?php echo $historyjson['errormsg']; ?></div>
    <?php else : ?>
      	<?php foreach ($historyjson['data'] as $whse) : ?>
      		<div>
      			<h3><?= $whse['Whse Name']; ?></h3>
      			<?php include $config->paths->content."item-information/tables/sales-history-formatted.php"; ?>
      		</div>
      	<?php endforeach; ?>
    <?php endif; ?>

<?php else : ?>
    <div class="alert alert-warning" role="alert">Information Not Available</div>
<?php endif; ?>
