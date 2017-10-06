<?php include('./_head.php'); // include header markup ?>
    <div class="jumbotron pagetitle">
        <div class="container">
            <h1><?php echo $page->get('pagetitle|headline|title') ; ?></h1>
        </div>
    </div>
    <div class="container page">
        <?php
			if ($input->urlSegment(1)) {
                include $config->paths->content."vend-information/vend-info-outline.php";
			} else {
                $input->get->function = 'vi';
                include $config->paths->content."vendor/ajax/load/vend-index/search-form.php";
			}
		?>
    </div>
<?php include('./_foot-with-toolbar.php'); // include footer markup ?>
