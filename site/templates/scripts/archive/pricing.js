$(function() {
	$(edit_price).click(function(event) {

		var button = $(this);

       /* $('.margin-selected').removeClass('margin-selected');
		if (button.hasClass('edit-price')) {
			$(button).parent().parent().addClass('margin-selected');
		} else {
			 $(button).addClass('margin-selected');
		}*/

		var itemid = button.data('itemid');
		var price = button.data('price');
		var qty = button.data('qty');
		var cost = button.data('cost');
		var uom = button.data('uom');
		var linenbr = button.data('lnbr');
		var discount_percent = button.data('disc');
		var listprice = button.data('listprice');
		var rqstdate = button.data('rqstdate');

		var default_warehouse = button.data('whse');
		draw_warehouse_table(itemid, default_warehouse, tbl, select_id);
		$('#whse-qty').val(qty);
		$('#whse-rqstdate').val(rqstdate);
		$('#whse-linenbr').val(linenbr);
		$('#detail-whse').val(default_warehouse);
		$('.linenbr').text(linenbr);

		$(item_price).val(price);

		$('#listprice').text(listprice).formatCurrency();
		$('.item').text(itemid);

		$('#apply-to').html('<b>' + itemid + '</b>');
		$('input[name="linenbr"]').val(linenbr);
		if (listprice == 0.00) {
			$(discount_input).attr('disabled', true);
		} else {
			$(discount_input).attr('disabled', false);
		}
		$(discount_input).val(discount_percent);
		$('#itemid-input').val(itemid);
        $('#override-itemid').val(itemid);
		$('#rqst-date').val(rqstdate);

		$('#show-item').html('Item ID: ' + itemid + ' - Original Price : <span> </span>');
		$('#show-item span').text(price);
		$('#show-item span').formatCurrency();
		$(qty_output).val(qty);
		$('#uofm').text(uom);
		$(discount_input).val(discount_percent);
        $(cost_output).text(cost).formatCurrency();

		$(item_price).change();

	});

	$(item_price).change(function() {
		var itemprice = $(item_price).val().replace('$','');
		var listprice = $(list_price).text().replace('$','');
		if (listprice == 0.00) {
			$(discounted_price).text("0.00").formatCurrency();
			var total_price = itemprice * $(qty_output).val();
			$(total_price_output).text(total_price).formatCurrency();
			$(discount_output).text("0.00").formatCurrency();
		} else {
			var discount_pct = ((listprice - itemprice) / listprice) * 100;
			var discount_amt = (listprice - itemprice);
			if (discount_pct < 0) {
				discount_pct = '';
			}
			$(discount_input).val(discount_pct);
			if (discount_amt < 0 ) {
				discount_amt = 0.00;
				$(discounted_price).text(itemprice).formatCurrency();
			} else {
				$(discounted_price).text(listprice - discount_amt).formatCurrency();
			}
			$(discount_output).text(discount_amt).formatCurrency();
			var total_price = itemprice * $(qty_output).val();
			$(total_price_output).text(total_price).formatCurrency();

		}

		var linenbr = $('input[name="linenbr"]').val();
		$('#'+linenbr+'-qtyo').text($(qty_output).val());
		console.log('#'+linenbr+'-qtyo');
		calculate_margin();

	});


	$(discount_input).change(function() {
		var disc_pct = $(this).val() / 100;
		var listprice = $(list_price).text().replace('$','');
		var discount_amt = disc_pct * listprice;
		var price = listprice - discount_amt;
		$(item_price).val(price).change();

	});

	$(margin_output).change(function() {
		var margin = $(this).val();
		var cost = $(cost_output).text().replace('$','');
		var price = calculate_price_from_margin(margin, cost);
		$(item_price).val(price).formatCurrency().change();
	});

	$(qty_output).change(function() {
		$(item_price).change();

	});

	function calculate_margin() {
		var price = $(item_price).val().replace('$','');
		var cost = $(cost_output).text().replace('$','');
		var margin = ((price - cost) / price * 100);
		$(margin_output).val(margin);
	}

	function calculate_price_from_margin(margin, cost) {
		var price = ((cost) / (1 - (margin / 100)));
		return price;
	}
});
