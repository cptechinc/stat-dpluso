<form action="<?= $config->pages->cart.'redir/'; ?>" method="post" class="quick-entry-add">
	<input type="hidden" name="action" value="add-to-quote">
	<div class="row">
		<div class="col-xs-9 sm-padding">
			<div class="row">
				<div class="col-md-4 form-group sm-padding">
					<span class="detail-line-field-name">Item/Description:</span>
					<span class="detail-line-field numeric">
						<input class="form-control input-xs underlined" type="text" name="itemID" placeholder="Item ID">
					</span>
				</div>
				<div class="col-md-1 form-group sm-padding"> </div>
				<div class="col-md-1 form-group sm-padding">
					<span class="detail-line-field-name">Qty:</span>
					<span class="detail-line-field numeric">
						<input class="form-control input-xs text-right underlined" type="text" size="6" name="qty" value="">
					</span>
				</div>
				<div class="col-md-2 form-group sm-padding">
					<span class="detail-line-field-name">Price:</span>
					<span class="detail-line-field numeric">
						<input class="form-control input-xs text-right underlined" type="text" size="10" name="price" value="">
					</span>
				</div>
				<div class="col-md-2 form-group sm-padding">
					<button type="submit" class="btn btn-sm btn-primary">
						<span class="glyphicon glyphicon-plus" aria-hidden="true"></span> &nbsp; Add
					</button>
				</div>
				<div class="col-md-2 form-group sm-padding">
					<button type="button" class="btn btn-sm btn-primary"  data-toggle="modal" data-target="#item-lookup-modal">
						<span class="glyphicon glyphicon-search" aria-hidden="true"></span> &nbsp; Search Items
					</button>
				</div>
			</div>
		</div>
		<div class="col-xs-3 col-sm-3"> </div>
	</div>
</form>
