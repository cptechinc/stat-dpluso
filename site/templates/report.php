<?php 
$config->scripts->append(hashtemplatefile('scripts/libs/raphael.js'));
$config->scripts->append(hashtemplatefile('scripts/libs/morris.js'));

?>
<?php include('./_head.php'); // include header markup ?>
    <div class="jumbotron pagetitle">
        <div class="container">
            <h1><?php echo $page->get('pagetitle|headline|title') ; ?></h1>
        </div>
    </div>
    <div class="container page">
        <?php include $config->paths->content."reports/$page->name.php"; ?>
    </div>
<?php include('./_foot.php'); // include footer markup ?>
