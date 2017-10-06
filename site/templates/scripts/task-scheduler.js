$(function() {
	$("body").on('changed.fu.scheduler', '#task-scheduler', function() { 
		console.log($('#task-scheduler').scheduler('value').recurrencePattern);
		$('#new-task-schedule-form').find('.icalpattern').val($('#task-scheduler').scheduler('value').recurrencePattern);
	});
	
	
	$("body").on("submit", "#new-task-schedule-form", function(e) {
		e.preventDefault();
		var form = $(this);
		var modal = form.data('modal');
		var formid = "#"+$(this).attr('id');
		$(formid).postform({formdata: false, jsoncallback: true}, function(json) {
			console.log(json.response.error);
			
			$.notify({
				icon: json.response.icon,
				message: json.response.message,
			},{
				element:  modal + " .modal-body",
				type: json.response.notifytype,
				url_target: '_self',
				placement: {
					from: "top",
					align: "center"
				}, 
				onClosed: function() {
					if (json.response.error) {
						
					} else {
						$(modal).modal('hide');
					}
				}
			});
		});
	});

});
	

	