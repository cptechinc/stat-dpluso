$(function() {
	$('#contacts-div').on('shown.bs.collapse', function () {
		if ($(this).data('tableloaded') === "no") {
			$(this).data('tableloaded', "yes");
			var table = $('#contacts-table').DataTable({
				"columnDefs": [
					{ "targets": [ 5 ], "visible": false, "bRegex": true, "bSmart": false },
				]
			});

			$('#limit-shiptos').change(function() {
				if ($(this).is(':checked')) {
					if ($(this).val().length > 0) {
						table.columns( 1 ).search("^" + this.value + "$", true, false, true).draw();
					}
				} else {
					table.columns( 1 ).search('').draw();
				}
			});
			$('#limit-cc').change(function() {
				if ($(this).is(':checked')) {
					table.columns( 5 ).search("^" + 'CC' + "$", true, false, true).draw();
				} else {
					table.columns( 5 ).search('').draw();
				}
			});
			if ($(this).data('shipid') !== '') {
				$('#limit-shiptos').bootstrapToggle('on');
			}
		}
	});
});




function refreshshipto(shipID, custID) {
	if (shipID.trim() != '') {
		location.href = config.urls.customer.page+custID+'/shipto-'+shipID+'/';
	} else {
		location.href = config.urls.customer.page+custID+'/';
	}
}
