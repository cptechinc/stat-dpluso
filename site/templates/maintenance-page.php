<?php 
    $returnpage = $input->get->returnpage ? $input->get->text('returnpage') : $config->pages->index;
    if (!$page->hasChildren('completed=0')) {
        $session->redirect("$returnpage");
    }
?> 
<?php include('./_head-blank.php'); ?>

<div class="jumbotron pagetitle">
    <div class="container">
        <h1><?php echo $page->get('pagetitle|headline|title') ; ?>&ensp;<i class="fa fa-cogs" aria-hidden="true"></i>
</h1>
    </div>
</div>
<div class="container page">
    <h2>We're Sorry</h2>
    <p>Our site is temporarily down for maintenance. See below for tasks currently taking place.</p></br>
    <h3>Current Maintenance Schedule</h3>
    <table class="table table-striped table-bordered table-condensed">
        <thead>
            <th>Event</th> <th>Description</th> <th>Start Time</th> <th>End Time</th>
        </thead>
        <?php $children = $page->children; ?>
        <?php foreach ($children as $child) : ?>
            <?php if ($child->completed == '0') : ?>
                <tr>
                    <td><?= $child->title; ?></td>
                    <td><?= $child->event_description; ?></td>
                    <td><?= $child->start; ?></td>
                    <td><?= $child->end; ?></td>
                </tr>
            <?php endif; ?>
        <?php endforeach; ?>
    </table>
</div>        

<?php include('./_foot-blank.php') ?>
