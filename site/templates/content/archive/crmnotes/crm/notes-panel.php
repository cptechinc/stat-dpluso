<?php
	$ajax = new stdClass();
	$ajax->link = $notepanel->getpanelrefreshlink();
	$ajax->data = $notepanel->data;
	$ajax->insertafter = $notepanel->getinsertafter();


	//notecount is defined in the portion that includes this file
	$totalcount = $notepanel->count;
?>
<div class="panel panel-primary not-round" id="notes-panel">
    <div class="panel-heading not-round">
    	<a href="#notes-div" class="panel-link" data-parent="#notes-panel" data-toggle="collapse">
        	<i class="fa fa-sticky-note" aria-hidden="true"></i> &nbsp; <?= $notepanel->getpaneltitle(); ?> <span class="caret"></span>
        </a>
         &nbsp;&nbsp;<span class="badge"> <?= $notepanel->count; ?></span>
		<?php if ($notepanel->needsaddnotelink()) : ?>
			<a href="<?= $notepanel->getaddnotelink(); ?>" class="btn btn-info btn-xs pull-right load-crm-note add-note hidden-print" data-modal="<?= $notepanel->modal; ?>" role="button" title="Add Note">
	            <i class="material-icons md-18">&#xE146;</i>
	        </a>
		<?php endif; ?>
        <span class="pull-right">&nbsp; &nbsp;&nbsp; &nbsp;</span>
        <a href="<?= $notepanel->getpanelrefreshlink(); ?>" class="btn btn-info btn-xs pull-right load-link notes-refresh hidden-print" <?= $notepanel->data; ?> role="button" title="Refresh Notes">
            <i class="material-icons md-18">&#xE86A; </i> <span class="hidden-xs">Refresh Notes</span>
        </a>
		<span class="pull-right"><?php if ($input->pageNum > 1 ) {echo 'Page '.$input->pageNum;} ?> &nbsp; &nbsp;</span>
    </div>
    <div id="notes-div" class="<?= $notepanel->collapse ?>">
        <div>
        	<div class="panel-body">
            	<div class="row">
                	<div class="col-xs-6"><?php include $config->paths->content.'pagination/ajax/pagination-start.php'; ?></div>
                    <div class="col-xs-6"><b><?php if ($input->pageNum > 1 ) {echo 'Page '.$input->pageNum;} ?></b> 	</div>
                </div>
            </div>
             <?php include $config->paths->content.'notes/crm/lists/'.$notepanel->type.'-notes-list.php'; ?>
             <?php $totalpages = ceil($totalcount / $config->showonpage); ?>
             <?php include $config->paths->content.'pagination/ajax/pagination-links.php'; ?>
        </div>
    </div>
</div>
