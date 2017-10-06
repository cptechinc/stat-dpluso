<?php
    $config->scripts->append(hashtemplatefile('scripts/libs/datatables.js'));
    $config->scripts->append(hashtemplatefile('scripts/pages/dashboard.js'));
    $config->scripts->append(hashtemplatefile('scripts/dplusnotes/order-notes.js'));
?>
<?php include('./_head.php'); // include header markup ?>
    <div class="jumbotron pagetitle">
        <div class="container">
            <h1><?= $page->get('pagetitle|headline|title') ; ?></h1>
        </div>
    </div>
    <div class="container page">
        <?php include $config->paths->content.'dashboard/dashboard-page-outline.php'; ?>
    </div>
<?php include('./_foot.php'); // include footer markup ?>
