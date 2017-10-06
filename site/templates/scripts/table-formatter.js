var tablejson = { maxcolumns: 0, rowcount: 0, rows: [] };
if (tabletype == 'sales-order') {
	tablejson = {maxcolumns:0, detail: {rowcount: 0, rows: []}};
} else if (tabletype == 'sales-history') {
	tablejson = {maxcolumns:0, detail: {rowcount: 0, rows: []}, lotserial: {rowcount: 0, rows: []}};
} else if (tabletype == 'purchase-order') {
	tablejson = {maxcolumns:0, detail: {rowcount: 0, rows: []}};
} else if (tabletype == 'purchase-history') {
	tablejson = {maxcolumns:0, detail: {rowcount: 0, rows: []}, lotserial: {rowcount: 0, rows: []}};
} else if (tabletype == 'quote') {
	tablejson = {maxcolumns:0, header: {rowcount: 0, rows: []}, detail: {rowcount: 0, rows: []}};
} else if (tabletype == 'open-invoices') {
	tablejson = {maxcolumns:0, detail: {rowcount: 0, rows: []}};
} else if (tabletype == 'payment-history') {
	tablejson = {maxcolumns:0, detail: {rowcount: 0, rows: []}};
} else if (tabletype == 'sales-orders') {
	tablejson = {maxcolumns:0, header: {rowcount: 0, rows: []}, detail: {rowcount: 0, rows: []}, itemstatus: {rowcount: 0, rows: []}, purchaseorder: {rowcount: 0, rows: []}, total: {rowcount: 0, rows: []}, shipments: {rowcount: 0, rows: []} };
} else if (tabletype == 'sales-history') {
	tablejson = {maxcolumns:0, header: {rowcount: 0, rows: []}, detail: {rowcount: 0, rows: []}, lotserial: {rowcount: 0, rows: []}, total: {rowcount: 0, rows: []}, shipments: {rowcount: 0, rows: []} };
}



$(function() {
	$('.screen-formatter-form').submit(function(e) {
		e.preventDefault();
		var formid = '#'+$(this).attr('id');
		countrows($(this));
		countcolumns();
		$('.detail-rows').val(tablejson.detail.rowcount);
		if (tablejson.header) {	$('.header-rows').val(tablejson.header.rowcount); }
		if (tablejson.lotserial) {	$('.lotserial-rows').val(tablejson.lotserial.rowcount); }
		if (tablejson.itemstatus) {	$('.itemstatus-rows').val(tablejson.itemstatus.rowcount); }
		if (tablejson.purchaseorder) {	$('.purchaseorder-rows').val(tablejson.purchaseorder.rowcount); }
		if (tablejson.total) {	$('.total-rows').val(tablejson.purchaseorder.total); }
		if (tablejson.shipments) {	$('.shipments-rows').val(tablejson.shipments.total); }
		console.log(formid);
		$('.cols').val(tablejson.maxcolumns);
		$(formid).postform({formdata: false, jsoncallback: true}, function(json) { //{formdata: data/false, jsoncallback: true/false}
			console.log('posting');
			console.log(json);
			if (json.response.error) {
				swal({
					title: "Error!",
					text: json.response.message,
					type: "warning",
					showCancelButton: true,
					confirmButtonText: "Yes, overwrite it",
					cancelButtonText: "Cancel",
					html: true
				},
				function(isConfirm){
					if (isConfirm) {
						$(formid).postform({formdata: $(formid).serializeform({ action: 'edit-formatter'}), jsoncallback: true}, function(json) {
							$.notify({
								icon: json.response.icon,
								message: json.response.message,
							},{
								element: "body",
								type: json.response.notifytype,
								url_target: '_self',
								placement: {
									from: "top",
									align: "center"
								}
							});
						});

					} else {

					}
				});
			} else {
				$.notify({
					icon: json.response.icon,
					message: json.response.message,
				},{
					element: "body",
					type: json.response.notifytype,
					url_target: '_self',
					placement: {
						from: "top",
						align: "center"
					}
				});
			}
		});
	});
});




function previewtable(formid) {
	var form = $(formid);
	countrows(form);
	countcolumns();
	$('.detail-rows').val(tablejson.detail.rowcount);
	$('.cols').val(tablejson.maxcolumns);
	if (tablejson.header) {	$('.header-rows').val(tablejson.header.rowcount); }
	if (tablejson.lotserial) {	$('.lotserial-rows').val(tablejson.lotserial.rowcount); }
	if (tablejson.itemstatus) {	$('.itemstatus-rows').val(tablejson.itemstatus.rowcount); }
	if (tablejson.purchaseorder) {	$('.purchaseorder-rows').val(tablejson.purchaseorder.rowcount); }
	if (tablejson.total) {	$('.total-rows').val(tablejson.purchaseorder.total); }
	if (tablejson.shipments) {	$('.shipments-rows').val(tablejson.shipments.total); }
	console.log(tablejson);
	drawformattable(tablejson);

}

function drawformattable(json) {
	$('#generated').remove();
	var table = "<table class='table table-striped table-condensed table-bordered table-excel' id='generated'></table>";
	var tabledata = '';

	tabledata += drawrowheadings(json.detail);
	tabledata += drawtbody(json.detail);

	if (tablejson.lotserial) {
		tabledata += drawrowheadings(json.lotserial);
		tabledata += drawtbody(json.lotserial);
	}

	if (tablejson.itemstatus) {
		tabledata += drawrowheadings(json.itemstatus);
		tabledata += drawtbody(json.itemstatus);
	}

	if (tablejson.purchaseorder) {
		tabledata += drawrowheadings(json.purchaseorder);
		tabledata += drawtbody(json.purchaseorder);
	}

	if (tablejson.total) {
		//tabledata += drawrowheadings(json.total);
		//tabledata += drawtbody(json.total);
		tabledata += drawheaderandbodyrow(json.total);
	}

	if (tablejson.shipments) {
		tabledata += drawrowheadings(json.shipments);
		tabledata += drawtbody(json.shipments);
	}

	$(table).html(tabledata).appendTo('.formatter-response');
}


function countrows(form) {
	form.find('.detail-line').each(function(index) {
		if ($(this).val() !== '' && $(this).val() !== '0') {
			var row = $(this).closest('tr');
			row.addClass('detail-line-'+$(this).val()).addClass('not-empty');
			if (parseInt($(this).val()) > tablejson.detail.rowcount) { tablejson.detail.rowcount = parseInt($(this).val()); }
		}
	});

	if (tablejson.header) {
		form.find('.header-line').each(function(index) {
			if ($(this).val() !== '' && $(this).val() !== '0') {
				var row = $(this).closest('tr');
				row.addClass('lotserial-line-'+$(this).val()).addClass('not-empty');
				if (parseInt($(this).val()) > tablejson.header.rowcount) { tablejson.header.rowcount = parseInt($(this).val()); }
			}
		});
	}

	if (tablejson.lotserial) {
		form.find('.lotserial-line').each(function(index) {
			if ($(this).val() !== '' && $(this).val() !== '0') {
				var row = $(this).closest('tr');
				row.addClass('lotserial-line-'+$(this).val()).addClass('not-empty');
				if (parseInt($(this).val()) > tablejson.lotserial.rowcount) { tablejson.lotserial.rowcount = parseInt($(this).val()); }
			}
		});
	}

	if (tablejson.itemstatus) {
		form.find('.itemstatus-line').each(function(index) {
			if ($(this).val() !== '' && $(this).val() !== '0') {
				var row = $(this).closest('tr');
				row.addClass('itemstatus-line-'+$(this).val()).addClass('not-empty');
				if (parseInt($(this).val()) > tablejson.itemstatus.rowcount) { tablejson.purchaseorder.rowcount = parseInt($(this).val()); }
			}
		});
	}

	if (tablejson.purchaseorder) {
		form.find('.purchaseorder-line').each(function(index) {
			if ($(this).val() !== '' && $(this).val() !== '0') {
				var row = $(this).closest('tr');
				row.addClass('purchaseorder-line-'+$(this).val()).addClass('not-empty');
				if (parseInt($(this).val()) > tablejson.purchaseorder.rowcount) { tablejson.purchaseorder.rowcount = parseInt($(this).val()); }
			}
		});
	}

	if (tablejson.total) {
		form.find('.total-line').each(function(index) {
			if ($(this).val() !== '' && $(this).val() !== '0') {
				var row = $(this).closest('tr');
				row.addClass('total-line-'+$(this).val()).addClass('not-empty');
				if (parseInt($(this).val()) > tablejson.total.rowcount) { tablejson.total.rowcount = parseInt($(this).val()); }
			}
		});
	}

	if (tablejson.shipments) {
		form.find('.shipments-line').each(function(index) {
			if ($(this).val() !== '' && $(this).val() !== '0') {
				var row = $(this).closest('tr');
				row.addClass('shipments-line-'+$(this).val()).addClass('not-empty');
				if (parseInt($(this).val()) > tablejson.shipments.rowcount) { tablejson.shipments.rowcount = parseInt($(this).val()); }
			}
		});
	}
}

function countcolumns() {
	$('.has-warning').removeClass('has-warning');
	$('.has-error').removeClass('has-error');

	var section = tablejson.detail;
	var sectionline = '.detail-line-';

	tablejson.detail = countsectioncolumns(section, sectionline);

	if (tablejson.header) {
		sectionline = '.header-line-';
		tablejson.header = countsectioncolumns(tablejson.header, sectionline);
	}

	if (tablejson.lotserial) {
		sectionline = '.lotserial-line-';
		tablejson.lotserial = countsectioncolumns(tablejson.lotserial, sectionline);
	}

	if (tablejson.itemstatus) {
		sectionline = '.itemstatus-line-';
		tablejson.itemstatus = countsectioncolumns(tablejson.itemstatus, sectionline);
	}

	if (tablejson.purchaseorder) {
		sectionline = '.purchaseorder-line-';
		tablejson.purchaseorder = countsectioncolumns(tablejson.purchaseorder, sectionline);
	}

	if (tablejson.total) {
		sectionline = '.total-line-';
		tablejson.total = countsectioncolumns(tablejson.total, sectionline);
	}

	if (tablejson.shipments) {
		sectionline = '.shipments-line-';
		tablejson.shipments = countsectioncolumns(tablejson.shipments, sectionline);
	}
}

function countsectioncolumns(sectionjson, sectionline) {
	for (var i = 1; i < sectionjson.rowcount + 1; i++) {
		var columns = [];
		var columncount = 0;

		$(sectionline+i).find('.column-length').each(function() {
			var row = $(this).closest('tr');
			var colnumber = row.find('.column').val();
			var data = row.find('.field').text();
			var example = row.find('.example-data').val();
			var label = row.find('.col-label').val();
			var columnlength = 1;

			if ($(this).val() != '0' && colnumber != '0') {columnlength = $(this).val();} else {$(this).val('1');}
			columncount += parseInt(columnlength);
			var column = new Column(colnumber, columnlength, label, data, example);
			if (columns[colnumber]) {
				$(sectionline+i).each(function() {
					if ($(this).find('.column').val() == colnumber) {
						$(this).addClass('has-warning');
					}
				});
			}
			columns[colnumber] = column;

		});

		if (columncount > tablejson.maxcolumns) {
			tablejson.maxcolumns = columncount;
		}

		/* if (columncount !== tablejson.maxcolumns) {
			createalertpanel('.formatter-response .message', "Column count doesn't match max column count, check all the columns in the in row #"+i, 'Error!', 'warning');
			$(sectionline+i).addClass('has-error');

		} */

		sectionjson.rows[i] = columns;

	}

	return sectionjson;
}

function drawrowheadings(sectionjson) {
	var tabledata = '';
	for (var f = 1; f < sectionjson.rowcount + 1; f++) {
		tabledata += "<tr class='"+f+"'>";
			if (sectionjson.rows[f].length > 0) {
				for (var o = 1; o < tablejson.maxcolumns + 1; o++) {
					if (sectionjson.rows[f][o]) {
						var column = sectionjson.rows[f][o];

						tabledata += "<th colspan='"+column.length+"'>"+column.label+"</th>";

					} else {
						tabledata += "<th>&nbsp;  </th>";
					}
				}
			} else {
				for (var n = 1; n < tablejson.maxcolumns + 1; n++) {
					tabledata += "<th>&nbsp;  </th>";
				}
			}
		tabledata += "</tr>";
	}
	return tabledata;
}
function drawheaderandbodyrow(sectionjson) {
	var tabledata = '';
	for (var i = 1; i < sectionjson.rowcount + 1; i++) {
		tabledata += "<tr class='"+i+"'>";
			if (sectionjson.rows[i].length > 0) {
				for (var x = 1; x < tablejson.maxcolumns + 1; x++) {
					if (sectionjson.rows[i][x]) {
						var column = sectionjson.rows[i][x];
						tabledata += "<td colspan='"+column.length+"'>"+column.label+"</td>";
						tabledata += "<td colspan='"+column.length+"' data-col='"+column.colnumber+"'>"+column.example+"</td>";

					} else {
						tabledata += "<td>&nbsp;  </td> <td>&nbsp;  </td>";
					}
				}
			} else {
				for (var y = 1; y < tablejson.maxcolumns + 1; y++) {
					tabledata += "<td>&nbsp;  </td>";
				}
			}
		tabledata += "</tr>";
	}
	return tabledata;
}
function drawtbody(sectionjson) {
	var tabledata = '';
	for (var i = 1; i < sectionjson.rowcount + 1; i++) {
		tabledata += "<tr class='"+i+"'>";
			if (sectionjson.rows[i].length > 0) {
				for (var x = 1; x < tablejson.maxcolumns + 1; x++) {
					if (sectionjson.rows[i][x]) {
						var column = sectionjson.rows[i][x];

						tabledata += "<td colspan='"+column.length+"' data-col='"+column.colnumber+"'>"+column.example+"</td>";

					} else {
						tabledata += "<td>&nbsp;  </td>";
					}
				}
			} else {
				for (var y = 1; y < tablejson.maxcolumns + 1; y++) {
					tabledata += "<td>&nbsp;  </td>";
				}
			}
		tabledata += "</tr>";
	}
	return tabledata;
}

function Column(colnumber, length, label, data, example) {
	this.colnumber = colnumber;
	this.length = length;
	this.label = label;
	this.data = data;
	this.example = example;

}
