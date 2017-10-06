<?php
	$ajax = new stdClass();
	$ajax->link = $actionpanel->getpanelpaginationlink();
	$ajax->data = $actionpanel->data;
	$ajax->insertafter = $actionpanel->getinsertafter();

	$totalcount = $actionpanel->count;
	$salespersonjson = json_decode(file_get_contents($config->companyfiles."json/salespersontbl.json"), true);
	$salespersoncodes = array_keys($salespersonjson['data']);
?>

<div class="panel panel-primary not-round" id="<?= $actionpanel->panelid; ?>">
    <div class="panel-heading not-round" id="<?= $actionpanel->panelid.'-heading'; ?>">
    	<a href="<?= '#'.$actionpanel->panelbody; ?>" class="panel-link" data-parent="<?= $actionpanel->panelid; ?>" data-toggle="collapse">
        	<span class="glyphicon glyphicon-check"></span> &nbsp; <?php echo $actionpanel->getpaneltitle(); ?> <span class="caret"></span>  &nbsp;&nbsp;<span class="badge"><?= $actionpanel->count; ?></span>
        </a>

		<?php if ($actionpanel->needsaddactionlink()) : ?>
			<a href="<?= $actionpanel->getaddactiontypelink(); ?>" class="btn btn-info btn-xs add-action pull-right hidden-print" data-modal="<?= $actionpanel->modal; ?>" role="button" title="Add Action">
	            <i class="material-icons md-18">&#xE146;</i>
	        </a>
		<?php endif; ?>

        <span class="pull-right">&nbsp; &nbsp;&nbsp; &nbsp;</span>
        <a href="<?= $actionpanel->getpanelrefreshlink(); ?>" class="btn btn-info btn-xs load-link actions-refresh pull-right hidden-print" <?= $ajax->data; ?> role="button" title="Refresh Actions">
            <i class="material-icons md-18">&#xE86A;</i>
        </a>
        <span class="pull-right"><?php if ($input->pageNum > 1 ) {echo 'Page '.$input->pageNum;} ?> &nbsp; &nbsp;</span>
    </div>
    <div id="<?= $actionpanel->panelbody; ?>" class="<?= $actionpanel->collapse; ?>">
        <div>
        	<div class="panel-body">
				<div class="row">
					<div class="col-xs-4">
                        <select name="" id="" class="form-control change-action-type" data-link="<?php echo $actionpanel->getactiontyperefreshlink(); ?>" <?= $ajax->data; ?>>
                            <?php $actiontypes = $pages->get('/activity/')->children(); ?>
                            <?php foreach ($actiontypes as $actiontype) : ?>
								<?php if ($actiontype->name == $actionpanel->getactiontypepage()) : ?>
									<option value="<?= $actiontype->name; ?>" selected><?= $actiontype->title; ?></option>
								<?php else : ?>
									<option value="<?= $actiontype->name; ?>"><?= $actiontype->title; ?></option>
								<?php endif; ?>
                            <?php endforeach; ?>
                        </select>
					</div>
					<div class="col-xs-4">
						<?php if (!$user->hasrestrictions) : ?>
							<label>Change User</label>
							<select class="form-control input-sm change-actions-user" data-link="<?= $actionpanel->getpanelrefreshlink(); ?>" <?= $ajax->data; ?>>
								<?php foreach ($salespersoncodes as $salespersoncode) : ?>
									<?php if ($salespersonjson['data'][$salespersoncode]['splogin'] == $assigneduserID) : ?>
										<option value="<?= $salespersonjson['data'][$salespersoncode]['splogin']; ?>" selected><?= $salespersoncode.' - '.$salespersonjson['data'][$salespersoncode]['spname']; ?></option>
									<?php else : ?>
										<option value="<?= $salespersonjson['data'][$salespersoncode]['splogin']; ?>"><?= $salespersoncode.' - '.$salespersonjson['data'][$salespersoncode]['spname']; ?></option>
									<?php endif; ?>
                                <?php endforeach; ?>
							</select>
						<?php endif; ?>
					</div>
				</div>
            </div>
             <?php include $config->paths->content.'pagination/ajax/pagination-start-no-form.php'; ?>
             <?php include $config->paths->content.'actions/'.$actionpanel->getactiontypepage().'/lists/'.$actionpanel->type.'-list.php'; ?>
             <?php $totalpages = ceil($totalcount / $config->showonpage); ?>
             <?php include $config->paths->content.'pagination/ajax/pagination-links.php'; ?>
        </div>
    </div>
</div>
