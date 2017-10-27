<?php 
$vendorID = $shipfromID = '';

if ($input->urlSegment(1)) {
    $vendorID = $input->urlSegment(1);
    $page->title = 'VI: ' . get_vendorname($vendorID);
    $buttonsjson = json_decode(file_get_contents($config->jsonfilepath.session_id()."-vibuttons.json"), true);
    $toolbar = $config->paths->content."vend-information/toolbar.php";
    $page->body = $config->paths->content."vend-information/vend-info-outline.php";
    
    if ($input->urlSegment(2)) {
        $shipfromID = urldecode(str_replace('shipfrom-', '', $input->urlSegment(2)));
        $page->title .= ' - ' . $shipfromID;
        $buttonsjson = json_decode(file_get_contents($config->jsonfilepath.session_id()."-visfbuttons.json"), true);
        $page->body = $config->paths->content."vend-information/vend-shipfrom.php";
    } 
    
    
    $config->scripts->append(hashtemplatefile('scripts/vi/vend-functions.js'));
    $config->scripts->append(hashtemplatefile('scripts/vi/vend-info.js'));
    $config->scripts->append(hashtemplatefile('scripts/libs/raphael.js'));
    $config->scripts->append(hashtemplatefile('scripts/libs/morris.js'));
} else {
    $toolbar = false;
    $input->get->function = 'vi';
    $page->body = $config->paths->content."vendor/ajax/load/vend-index/search-form.php";
}
?>

<?php include('./_head.php'); // include header markup ?>
    <div class="jumbotron pagetitle">
        <div class="container">
            <h1><?php echo $page->get('pagetitle|headline|title') ; ?></h1>
        </div>
    </div>
    <div class="container page">
        <?php
			include $page->body;
		?>
    </div>
<?php include('./_foot-with-toolbar.php'); // include footer markup ?>
