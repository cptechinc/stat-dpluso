<?php include('./_head.php'); // include header markup ?>
    <div class="jumbotron pagetitle">
        <div class="container">
            <h1><?= $page->get('pagetitle|headline|title') ; ?></h1>
        </div>
    </div>
    <div class="container page">
        <?php include $page->body; ?>
    </div>
<?php include('./_foot-with-toolbar.php'); // include footer markup ?>
