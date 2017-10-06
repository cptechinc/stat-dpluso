<?php
	include $config->paths->assets."classes/php-ical/vendor/autoload.php";
	use RRule\RRule; $count = 0;
	$schedules = get_current_taskschedules();
	$today = date('Y-m-d', strtotime('2017-03-29'));
	$schedulecount = sizeof($schedules);
	if ($schedulecount > 0) {
		foreach ($schedules as $schedule) {
			$schedulerule = new RRule($schedule['repeatlogic']);
			$logic = $$schedulerule->getRule(); $logic['DTSTART'] = $today;
			$rule = new RRule($logic);
			if ($rule->occursAt($today)) {
				echo $schedule['id'] . " occurs today";
				scheduletask($schedule['user'], date("Y-m-d H:i:s"), date("Y-m-d 12:00:00"), $schedule['description'], $schedule['custlink'], $$schedule['shiptolink'], $$schedule['contactlink'], false);
				$count++;
			} else {
				if ($rule->isFinite()) {
					if (intval($rule[$rule->count()]->format('Y-m-d') ) < intval($date('Y-m-d'))) {
						change_taskschedule_active($schedule['id'], false, false);
					}
				}
			}

		}
	} else {
		echo ' No Scheduled Tasks were scheduled';
	}


?>
