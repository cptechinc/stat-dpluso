<?php
    $custID = $shipID = '';
    if ($input->get->itemID) {
        $itemID = $input->get->text('itemID');
		$page->title = 'II: ' . $itemID;
        $itemjson = json_decode(file_get_contents($config->jsonfilepath.session_id()."-iiitem.json"), true);
        $buttonsjson = json_decode(file_get_contents($config->jsonfilepath.session_id()."-iibuttons.json"), true);
		$toolbar = $config->paths->content."item-information/toolbar.php";
    } else {
		$toolbar = false;
        $q = '';
	}

    if ($input->get->custID) {
        $custID = $input->get->text('custID');
        if ($input->get->shipID) {
			$shipID = $input->get->text('shipID');
        }
    }
    $tableformatter = $page->screenformatterfactory->generate_screenformatter('ii-item-page');
    
    $config->scripts->append(hashtemplatefile('scripts/libs/raphael.js'));
    $config->scripts->append(hashtemplatefile('scripts/libs/morris.js'));
    $config->scripts->append(hashtemplatefile('scripts/libs/datatables.js'));
	$config->scripts->append(hashtemplatefile('scripts/ii/item-functions.js'));
	$config->scripts->append(hashtemplatefile('scripts/ii/item-info.js'));
?>
<?php include('./_head.php'); // include header markup ?>
    <div class="jumbotron pagetitle">
        <div class="container">
            <h1><?php echo $page->get('pagetitle|headline|title') ; ?></h1>
        </div>
    </div>
    <div class="container page">
		<?php if ($input->get->itemID) : ?>
			<div class="col-sm-2 hidden-print"> <?php include $config->paths->content."item-information/ii-buttons.php"; ?> </div>
			<div class="col-sm-10 print"><?php include $config->paths->content."item-information/ii-item-page.php";  ?></div>
		<?php else : ?>
			<?php include $config->paths->content."item-information/forms/item-search-form.php"; ?>
			<script>
				$(function() {$('.ii-item-search').focus();  listener.stop_listening();})
			</script>
		<?php endif; ?>
    </div>
<?php include('./_foot-with-toolbar.php'); // include footer markup ?>
