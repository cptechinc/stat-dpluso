<?php $actiontypes = $pages->get('/activity/actions/')->children(); ?>
<?php foreach ($actiontypes as $actiontype) : ?>
	<?php if (isset($action)) : ?>
		<?php if ($actiontype->name == $action->actionsubtype) : ?>
			<button class="btn btn-primary select-button-choice btn-sm" type="button" data-value="<?= $actiontype->name; ?>">
				<?= $actiontype->subtypeicon." ".$actiontype->actionsubtypelabel; ?>
			</button>
		<?php else : ?>
			<button class="btn btn-default select-button-choice btn-sm" type="button" data-value="<?= $actiontype->name; ?>">
				<?= $actiontype->subtypeicon." ".$actiontype->actionsubtypelabel; ?>
			</button>
		<?php endif; ?>
	<?php else : ?>
		<button class="btn btn-default select-button-choice btn-sm" type="button" data-value="<?= $actiontype->name; ?>">
			<?= $actiontype->subtypeicon." ".$actiontype->actionsubtypelabel; ?>
		</button>
	<?php endif; ?>

<?php endforeach; ?>

<input type="hidden" class="select-button-value required" name="actiontype" value="<?php echo $action->actionsubtype; ?>">
