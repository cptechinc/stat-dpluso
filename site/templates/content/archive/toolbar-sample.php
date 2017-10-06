<style>
/* The heart of the matter */
.toolbar-container > .toolbar, .button-toolbar > .btn-group.btn-group-justified {
  overflow-x: auto;
  white-space: nowrap;
}
.toolbar-container > .toolbar > .function-column, .button-toolbar > .btn-group.btn-group-justified > .btn-group  {
  display: inline-block;
  float: none;
	padding-left: 7px;
	padding-right: 7px;
}
	.toolbar > .function-column {
		
	}
	.function-column a {
		color: white;
		display: block;
	}

	.function a {
		padding-top: 15px;
		padding-bottom: 15px;
	}
</style>
<div class="toolbar-container hidden">
  <div class="toolbar text-center">
    <?php foreach ($buttonsjson['data'] as $button) : ?>
	<div class="function-column col-xs-4 col-md-2">
		<div class="function">
			<a href="#" class="btn-info" onClick="<?php echo $button['function'].'()'; ?>"> 	
				<?php echo $button['label']; ?>
			</a> 
		</div>
		
	</div>
	
	<?php endforeach; ?>
  </div>
</div>