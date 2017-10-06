
	var ele_frequency = '.repeat-type';
	var ele_repeatson = '.repeatson';
	var ele_every = '.every';
	var ele_everyweek = '.every-week';

	var choose_weekdays = '.weekdays';
	var choose_calendar = '.calendardays';

	var scheduledescription = '.schedule-desc';

	var label_interval = '.interval-label';

	$(function() {
		$('#task-scheduler').on('changed.fu.scheduler', function () {
			console.log($('#task-scheduler').scheduler('value').recurrencePattern);
		});
		$("body").on("change", ele_frequency, function() {
			$(ele_every).parent().addClass('hidden');
			$(ele_everyweek).parent().addClass('hidden');
			if ($(this).val() !== 'n/a') {
				switch ($(this).val()) {
					case 'monthweek':
						$(choose_weekdays).removeClass('hidden');
						$(choose_calendar).addClass('hidden');
						$(ele_repeatson).val('');
						$(ele_repeatson).parent().removeClass('hidden');
						$(ele_everyweek).parent().removeClass('hidden');
						break
					case 'monthday':
						$(choose_calendar).removeClass('hidden');
						$(choose_weekdays).addClass('hidden');
						$(ele_repeatson).val('');
						$(ele_repeatson).parent().removeClass('hidden');
						$(ele_every).parent().removeClass('hidden');
						break;
					case 'day':
						$(choose_calendar).addClass('hidden');
						$(choose_weekdays).addClass('hidden');
						$(ele_repeatson).parent().addClass('hidden');
						$(ele_every).parent().removeClass('hidden');
						break;
				}


				buildscheduledescription();
			}
		});

		$("body").on("change", ele_every, function() {
			buildscheduledescription();
		});


		$("body").on("click", '.day-number', function() {
			var daynumber = $(this).text();
			var modifier = $(ele_repeatson).val().trim();
			var days = '';
			if ($(this).hasClass('btn-primary')) {
				$(this).removeClass('btn-primary');

			} else {
				$(this).addClass('btn-primary');
			}

			$('.day-number').each(function(index, day) {
				if ($(this).hasClass('btn-primary')) {
					days += $(this).text() + ",";
				}
			});

			$(ele_repeatson).val(days.replace(/,+$/,''));

			buildscheduledescription();
		});

		$("body").on("change", '.week-days-select', function() {
			var repeatson = $(ele_repeatson).val().trim();
			var weekday = $(this).val();
			if (weekday !== 'n/a') {
				if (repeatson.indexOf(weekday) !== -1) {
					var regex = new RegExp("("+weekday+")");
					var commaregex = new RegExp("("+weekday+"),");
					if (commaregex.test(repeatson)) {
						$(ele_repeatson).val(repeatson.replace(commaregex,''));
					} else {
						$(ele_repeatson).val(repeatson.replace(regex,''));
					}
				} else {
					if (repeatson.length > 0) {
						$(ele_repeatson).val(repeatson + ', ' + weekday);
					} else {
						$(ele_repeatson).val(weekday);
					}
				}
			}
			buildscheduledescription();
		});

	});


	function buildscheduledescription() {
		var repeattype = $(ele_frequency).val();
		var frequency = $(ele_every).val();
		var repeat = $(ele_repeatson).val();

		switch (repeattype) {
			case 'monthweek':
				var desc = 'week';
				var frequency = $(ele_everyweek).val();
				if (frequency.length > 0) {
					if (repeat.length > 0) {
						$(scheduledescription).text('Every ' + getordinalsuffix(frequency) + ' ' + repeat + ' of the month');
					} else {
						$(scheduledescription).text('Every ' + getordinalsuffix(frequency) + ' ' + desc + ' of the month');
					}
				} else {
					$(scheduledescription).text('Every ' + desc + ' on ' + repeat);
				}

				break
			case 'monthday':
				var dates = '';
				var repeatarray = repeat.split(",");
				var desc = 'month';
				if (frequency.length > 0) {
					if (repeat.length > 0) {

						for (var i = 0; i < repeatarray.length; i++) {
							dates += getordinalsuffix(repeatarray[i]) + ",";
						}
						$(scheduledescription).text('Every ' + getordinalsuffix(frequency) + ' ' + desc + ' on the ' + dates.replace(/,+$/,''));
					} else {
						$(scheduledescription).text('Every ' + getordinalsuffix(frequency) + ' ' + desc);
					}
				} else {
					if (repeat.length > 0) {
						for (var h = 0; h < repeatarray.length; h++) {
							dates += getordinalsuffix(repeatarray[h]) + ",";
						}
						$(scheduledescription).text('Every '  + desc + ' on the ' + dates.replace(/,+$/,''));
					} else {
						$(scheduledescription).text('Every '  + desc);
					}
				}
				$(label_interval).text('Month Interval');
				break;
			case 'day':
				if (frequency.length > 0) {
					if (frequency > 1 ) {repeattype += "s";}
					$(scheduledescription).text('Every ' + frequency + ' ' + repeattype);
				} else {
					$(scheduledescription).text('Every ' + repeattype);
				}
				$(label_interval).text('Day Interval');
				break;
		}

	}
