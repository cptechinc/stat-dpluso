<?php
    $message = "Create a task schedule for {replace} ";
    $title = createmessage($message, $custID, $shipID, $contactID, $taskID, $noteID, $ordn, $qnbr);
	$timeunits = json_decode(file_get_contents($config->paths->assets."jsonconfigs/time-units.json"), true);
?>
<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
    <h4 class="modal-title" id="ajax-modal-label"><?= $title; ?></h4>
</div>
<div class="modal-body scroll">
	<div class="response"></div>

	<form action="<?= $config->pages->taskschedule."add/"; ?>" id="new-task-schedule-form" method="post" data-modal="#ajax-modal">
		<input type="hidden" name="action" value="create-task-schedule">
		<input type="hidden" name="customerlink" value="<?= $custID; ?>">
		<input type="hidden" name="shiptolink" value="<?= $shipID; ?>">
		<input type="hidden" name="contactlink" value="<?= $contactID; ?>">
		<input type="hidden" name="icalvalue" class="icalpattern" value="">
		<div class="form-horizontal container-fluid scheduler" data-initialize="scheduler" role="form" id="task-scheduler">
			<div class="row start-datetime">
				<label class="col-sm-2 control-label scheduler-label" for="myStartDate">Start Date</label>
				<div class="col-sm-10">
					<div class="row no-margin">
						<div class="col-xs-4 col-sm-4 form-group">
							<div class="datepicker start-date">
								<div class="input-group">
									<input type="text" class="form-control" id="task-start-date" name="start-date" />
									<div class="input-group-btn">
										<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
											<span class="glyphicon glyphicon-calendar"></span>
											<span class="sr-only">Toggle Calendar</span>
										</button>
										<div class="dropdown-menu dropdown-menu-right datepicker-calendar-wrapper" role="menu">
											<div class="datepicker-calendar">
												<div class="datepicker-calendar-header">
													<button type="button" class="prev">
														<span class="glyphicon glyphicon-chevron-left"></span><span class="sr-only">Previous Month</span>
													</button>
													<button type="button" class="next">
														<span class="glyphicon glyphicon-chevron-right"></span><span class="sr-only">Next Month</span>
													</button>
													<button type="button" class="title">
														<span class="month">
															<?php for ($i = 0; $i < sizeof($timeunits['months']); $i++) : ?>
																<span data-month="<?= $i; ?>"><?= $timeunits['months'][$i]['long']; ?></span>
															<?php endfor; ?>
														</span>
														<span class="year"></span>
													</button>
												</div>
												<table class="datepicker-calendar-days">
													<thead>
														<tr>
															<?php foreach ($timeunits['weekdays'] as $weekday) {'<th>'.ucfirst(substr($weekday,0,2)).'</th>';} ?>
														</tr>
													</thead>
													<tbody></tbody>
												</table>
												<div class="datepicker-calendar-footer">
													<button type="button" class="datepicker-today">Today</button>
												</div>
											</div>
											<div class="datepicker-wheels" aria-hidden="true">
												<div class="datepicker-wheels-month">
													<h2 class="header">Month</h2>
													<ul>
														<?php for ($i = 0; $i < sizeof($timeunits['months']); $i++) : ?>
															<li data-month="<?= $i; ?>">
																<button type="button"><?= $timeunits['months'][$i]['short']; ?></button>
															</li>
														<?php endfor; ?>
													</ul>
												</div>
												<div class="datepicker-wheels-year">
													<h2 class="header">Year</h2>
													<ul></ul>
												</div>
												<div class="datepicker-wheels-footer clearfix">
													<button type="button" class="btn datepicker-wheels-back">
														<span class="glyphicon glyphicon-arrow-left"></span><span class="sr-only">Return to Calendar</span>
													</button>
													<button type="button" class="btn datepicker-wheels-select">
														Select <span class="sr-only">Month and Year</span>
													</button>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="col-xs-4 col-sm-4 form-group">
							<label class="sr-only" for="MyStartTime">Start Time</label>
							<div class="input-group combobox start-time">
								<input id="MyStartTime" type="text" class="form-control" />
								<div class="input-group-btn">
									<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
										<span class="caret"></span>
										<span class="sr-only">Toggle Dropdown</span>
									</button>
									<ul class="dropdown-menu dropdown-menu-right" role="menu">
										<?php foreach ($timeunits['clock'] as $time) : ?>
											<li data-value="<?= $time." AM"; ?>"><a href="#"><?= $time." AM"; ?></a></li>
										<?php endforeach; ?>
										<?php foreach ($timeunits['clock'] as $time) : ?>
											<li data-value="<?= $time." PM"; ?>"><a href="#"><?= $time." PM"; ?></a></li>
										<?php endforeach; ?>
									</ul>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>

			<div class="row timezone-container hidden">
				<label class="col-sm-2 control-label scheduler-label">Timezone</label>
				<div class="col-xs-10 col-sm-10 col-md-10">
					<div data-resize="auto" class="btn-group selectlist timezone">
						<button data-toggle="dropdown" class="btn btn-default dropdown-toggle" type="button">
							<span class="selected-label">(GMT-06:00) Central Standard Time</span>
							<span class="caret"></span>
							<span class="sr-only">Toggle Dropdown</span>
						</button>
						<ul class="dropdown-menu" role="menu">
							<?php foreach ($timeunits['offsets'] as $offset) : ?>
								<li data-name="<?= $offset['name']; ?>" data-offset="<?= $offset['offset']; ?>"><a href="#"><?= $offset['desc']; ?></a></li>
							<?php endforeach; ?>
						</ul>
						<input type="text" aria-hidden="true" readonly name="TimeZoneSelectlist" class="hidden hidden-field">
					</div>
				</div>
			</div>

			<div class="row repeat-container">
				<label class="col-sm-2 control-label scheduler-label">Repeat</label>
				<div class="col-sm-10">

					<div class="form-group repeat-interval">
						<div data-resize="auto" class="btn-group selectlist pull-left repeat-options">
							<button data-toggle="dropdown" class="btn btn-default dropdown-toggle" type="button">
								<span class="selected-label">Daily</span> <span class="caret"></span>
							</button>
							<ul class="dropdown-menu" role="menu">
								<li data-value="none" ><a href="#">Choose</a></li>
								<li data-value="daily" data-text="day(s)"><a href="#">Daily</a></li>
								<li data-value="weekdays"><a href="#">Weekdays</a></li>
								<li data-value="weekly" data-text="week(s)"><a href="#">Weekly</a></li>
								<li data-value="monthly" data-text="month(s)"><a href="#">Monthly</a></li>
								<li data-value="yearly"><a href="#">Yearly</a></li>
							</ul>
							<input type="text" aria-hidden="true" readonly name="intervalSelectlist" class="hidden hidden-field">
						</div>

						<div class="repeat-panel repeat-every-panel repeat-hourly repeat-daily repeat-weekly hide" aria-hidden="true">
							<label id="MySchedulerEveryLabel" class="inline-form-text repeat-every-pretext">every</label>
							<div class="spinbox digits-3 repeat-every">
								<input type="text" class="form-control input-mini spinbox-input" aria-labelledby="MySchedulerEveryLabel">
								<div class="spinbox-buttons btn-group btn-group-vertical">
									<button type="button" class="btn btn-default spinbox-up btn-xs">
										<span class="glyphicon glyphicon-chevron-up"></span><span class="sr-only">Increase</span>
									</button>
									<button type="button" class="btn btn-default spinbox-down btn-xs">
										<span class="glyphicon glyphicon-chevron-down"></span><span class="sr-only">Decrease</span>
									</button>
								</div>
							</div>
							<div class="inline-form-text repeat-every-text"></div>
						</div>
					</div>

					<div class="form-group repeat-panel repeat-weekly repeat-days-of-the-week hide" aria-hidden="true">
						<fieldset class="btn-group" data-toggle="buttons">
							<?php foreach ($timeunits['weekdays'] as $weekday) : ?>
								<label class="btn btn-default">
									<input type="checkbox" data-value="<?= strtoupper(substr($weekday,0,3)); ?>"><?= ucfirst(substr($weekday,0,3)); ?>
								</label>
							<?php endforeach; ?>
						</fieldset>
					</div>

					<div class="repeat-panel repeat-monthly hide" aria-hidden="true">
						<div class="form-group repeat-monthly-date">
							<div class="radio pull-left">
								<label class="radio-custom">
									<input class="sr-only" type="radio" checked="checked" name="repeat-monthly" value="bymonthday">
									<span class="radio-label">on day</span>
								</label>
							</div>
							<div data-resize="auto" class="btn-group selectlist pull-left">
								<button data-toggle="dropdown" class="btn btn-default dropdown-toggle" type="button">
									<span class="selected-label">1</span> <span class="caret"></span>
								</button>
								<ul class="dropdown-menu" role="menu" style="height:200px; overflow:auto;">
									<?php for ($i = 1; $i < 32; $i++) : ?>
										<li data-value="<?= $i; ?>"><a href="#"><?= $i; ?></a></li>
									<?php endfor; ?>
								</ul>
								<input type="text" aria-hidden="true" readonly name="monthlySelectlist" class="hidden hidden-field">
							</div>
						</div>

						<div class="repeat-monthly-day form-group">
							<div class="radio pull-left">
								<label class="radio-custom">
									<input class="sr-only" type="radio" checked="checked" name="repeat-monthly" value="bysetpos">
									<span class="radio-label">on the</span>
								</label>
							</div>

							<div data-resize="auto" class="btn-group selectlist month-day-pos pull-left">
								<button data-toggle="dropdown" class="btn btn-default dropdown-toggle" type="button">
									<span class="selected-label">First</span> <span class="caret"></span>
								</button>
								<ul class="dropdown-menu" role="menu">
									<li data-value="1"><a href="#">First</a></li>
									<li data-value="2"><a href="#">Second</a></li>
									<li data-value="3"><a href="#">Third</a></li>
									<li data-value="4"><a href="#">Fourth</a></li>
									<li data-value="-1"><a href="#">Last</a></li>
								</ul>
								<input type="text" aria-hidden="true" readonly name="monthlySelectlist" class="hidden hidden-field">
							</div>

							<div data-resize="auto" class="btn-group selectlist month-days pull-left">
								<button data-toggle="dropdown" class="btn btn-default dropdown-toggle" type="button">
									<span class="selected-label">Sunday</span> <span class="caret"></span>
								</button>
								<ul class="dropdown-menu" role="menu">
									<?php foreach ($timeunits['weekdays'] as $weekday) : ?>
										<li data-value="<?php echo strtoupper(substr($weekday,0,2)); ?>"><a href="#"><?php echo $weekday; ?></a></li>
									<?php endforeach; ?>
								</ul>
								<input type="text" aria-hidden="true" readonly name="monthlySelectlist" class="hidden hidden-field">
							</div>
						</div>
					</div>

					<div class="repeat-panel repeat-yearly hide" aria-hidden="true">
						<div class="form-group repeat-yearly-date">
							<div class="radio pull-left">
								<label class="radio-custom">
									<input class="sr-only" type="radio" checked="checked" name="repeat-yearly" value="bymonthday"> <span class="radio-label">on</span>
								</label>
							</div>

							<div data-resize="auto" class="btn-group selectlist year-month pull-left">
								<button data-toggle="dropdown" class="btn btn-default dropdown-toggle" type="button">
									<span class="selected-label">January</span> <span class="caret"></span>
								</button>
								<ul class="dropdown-menu" role="menu">
									<?php for ($i = 0; $i < sizeof($timeunits['months']); $i++) : ?>
										<li data-value="<?= $timeunits['months'][$i]['monthnumber']; ?>">
											<a href="#"><?= $timeunits['months'][$i]['long']; ?></a>
										</li>
									<?php endfor; ?>
								</ul>
								<input type="text" aria-hidden="true" readonly name="monthlySelectlist" class="hidden hidden-field">
							</div>

							<div data-resize="auto" class="btn-group selectlist year-month-day pull-left">
								<button data-toggle="dropdown" class="btn btn-default dropdown-toggle" type="button">
									<span class="selected-label">1</span> <span class="caret"></span>
								</button>
								<ul class="dropdown-menu" role="menu" style="height:200px; overflow:auto;">
									<?php for ($i = 1; $i < 32; $i++) : ?> <li data-value="<?= $i; ?>"><a href="#"><?= $i; ?></a></li> <?php endfor; ?>
								</ul>
								<input type="text" aria-hidden="true" readonly name="monthlySelectlist" class="hidden hidden-field">
							</div>
						</div>

						<div class="form-group repeat-yearly-day">

							<div class="radio pull-left">
								<label class="radio-custom"><input class="sr-only" type="radio" name="repeat-yearly" value="bysetpos"> on the</label>
							</div>

							<div data-resize="auto" class="btn-group selectlist year-month-day-pos pull-left">
								<button data-toggle="dropdown" class="btn btn-default dropdown-toggle" type="button">
									<span class="selected-label">First</span> <span class="caret"></span> <span class="sr-only">First</span>
								</button>
								<ul class="dropdown-menu" role="menu">
									<li data-value="1"><a href="#">First</a></li>
									<li data-value="2"><a href="#">Second</a></li>
									<li data-value="3"><a href="#">Third</a></li>
									<li data-value="4"><a href="#">Fourth</a></li>
									<li data-value="-1"><a href="#">Last</a></li>
								</ul>
								<input type="text" aria-hidden="true" readonly name="yearlyDateSelectlist" class="hidden hidden-field">
							</div>

							<div data-resize="auto" class="btn-group selectlist year-month-days pull-left">
								<button data-toggle="dropdown" class="btn btn-default dropdown-toggle" type="button">
									<span class="selected-label">Sunday</span> <span class="caret"></span> <span class="sr-only">Sunday</span>
								</button>
								<ul class="dropdown-menu" role="menu" style="height:200px; overflow:auto;">
									<?php foreach ($timeunits['weekdays'] as $weekday) : ?>
										<li data-value="<?php echo strtoupper(substr($weekday,0,2)); ?>"><a href="#"><?php echo $weekday; ?></a></li>
									<?php endforeach; ?>
									<li data-value="SU,MO,TU,WE,TH,FR,SA"><a href="#">Day</a></li>
									<li data-value="MO,TU,WE,TH,FR"><a href="#">Weekday</a></li>
									<li data-value="SU,SA"><a href="#">Weekend day</a></li>
								</ul>
								<input type="text" aria-hidden="true" readonly name="yearlyDaySelectlist" class="hidden hidden-field">
							</div>
							<div class="inline-form-text repeat-yearly-day-text"> of </div>

							<div data-resize="auto" class="btn-group selectlist year-month pull-left">
								<button data-toggle="dropdown" class="btn btn-default dropdown-toggle" type="button">
									<span class="selected-label">January</span> <span class="caret"></span> <span class="sr-only">January</span>
								</button>
								<ul class="dropdown-menu" role="menu" style="height:200px; overflow:auto;">
									<?php for ($i = 0; $i < sizeof($timeunits['months']); $i++) : ?>
										<li data-value="<?= $timeunits['months'][$i]['monthnumber']; ?>">
											<a href="#"><?= $timeunits['months'][$i]['long']; ?></a>
										</li>
									<?php endfor; ?>
								</ul>
								<input type="text" aria-hidden="true" readonly name="yearlyDaySelectlist" class="hidden hidden-field">
							</div>
						</div>
					</div>
				</div>
			</div>

			<div class="row repeat-end hide" aria-hidden="true">
				<label class="col-sm-2 control-label scheduler-label">End</label>
				<div class="col-sm-10">
					<div class="row">
						<div class="col-xs-3 col-sm-3 col-lg-2 form-group">
							<div data-resize="auto" class="btn-group selectlist end-options pull-left">
								<button data-toggle="dropdown" class="btn btn-default dropdown-toggle" type="button">
									<span class="selected-label">Never</span> <span class="caret"></span> <span class="sr-only">Never</span>
								</button>
								<ul class="dropdown-menu" role="menu">
									<li data-value="never"><a href="#">Never</a></li>
									<li data-value="after"><a href="#">After</a></li>
									<li data-value="date"><a href="#">On date</a></li>
								</ul>
								<input type="text" aria-hidden="true" readonly name="EndSelectlist" class="hidden hidden-field">
							</div>
						</div>

						<div class="col-sm-4 col-lg-4 form-group end-option-panel end-after-panel pull-left hide" aria-hidden="true">
							<div class="spinbox digits-3 end-after">
								<label id="MyEndAfter" class="sr-only">End After</label>
								<input type="text" class="form-control input-mini spinbox-input" aria-labelledby="MyEndAfter">
								<div class="spinbox-buttons btn-group btn-group-vertical">
									<button type="button" class="btn btn-default spinbox-up btn-xs">
										<span class="glyphicon glyphicon-chevron-up"></span><span class="sr-only">Increase</span>
									</button>
									<button type="button" class="btn btn-default spinbox-down btn-xs">
										<span class="glyphicon glyphicon-chevron-down"></span><span class="sr-only">Decrease</span>
									</button>
								</div>
							</div>
							<div class="inline-form-text end-after-text">occurrence(s)</div>
						</div>

						<div class="col-xs-4 col-sm-4 col-lg-4 form-group end-option-panel end-on-date-panel pull-left hide" aria-hidden="true">
							<div class="datepicker end-on-date">
								<div class="input-group">
									<input class="form-control" id="myEndDate" type="text"/>
									<div class="input-group-btn">
										<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
											<span class="glyphicon glyphicon-calendar"></span> <span class="sr-only">Toggle Calendar</span>
										</button>
										<div class="dropdown-menu dropdown-menu-right datepicker-calendar-wrapper" role="menu">
											<div class="datepicker-calendar">
												<div class="datepicker-calendar-header">
													<button type="button" class="prev">
														<span class="glyphicon glyphicon-chevron-left"></span><span class="sr-only">Previous Month</span>
													</button>
													<button type="button" class="next">
														<span class="glyphicon glyphicon-chevron-right"></span><span class="sr-only">Next Month</span>
													</button>
													<button type="button" class="title">
														<span class="month">
															<?php for ($i = 0; $i < sizeof($timeunits['months']); $i++) : ?>
																<span data-month="<?= $i; ?>"><?= $timeunits['months'][$i]['long']; ?></span>
															<?php endfor; ?>
														</span> <span class="year"></span>
													</button>
												</div>
												<table class="datepicker-calendar-days">
													<thead>
														<tr>
															<?php foreach ($timeunits['weekdays'] as $weekday) : ?>
																<th><?php echo ucfirst(substr($weekday,0,2)); ?></th>
															<?php endforeach; ?>
														</tr>
													</thead> <tbody></tbody>
												</table>
												<div class="datepicker-calendar-footer">
													<button type="button" class="datepicker-today">Today</button>
												</div>
											</div>
											<div class="datepicker-wheels" aria-hidden="true">
												<div class="datepicker-wheels-month">
													<h2 class="header">Month</h2>
													<ul>
														<?php for ($i = 0; $i < sizeof($timeunits['months']); $i++) : ?>
														<li data-month="<?= $i; ?>">
															<button type="button"><?= $timeunits['months'][$i]['short']; ?></button>
														</li>
														<?php endfor; ?>
													</ul>
												</div>
												<div class="datepicker-wheels-year"> <h2 class="header">Year</h2> <ul></ul> </div>
												<div class="datepicker-wheels-footer clearfix">
													<button type="button" class="btn datepicker-wheels-back">
														<span class="glyphicon glyphicon-arrow-left"></span><span class="sr-only">Return to Calendar</span>
													</button>
													<button type="button" class="btn datepicker-wheels-select">
														Select <span class="sr-only">Month and Year</span>
													</button>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
            <div class="row">
                <label class="col-sm-2 control-label" >Task Type</label>
                <div class="col-sm-6">
                    <?php include $config->paths->content."tasks/forms/select-task-type.php"; ?>
                </div>
            </div>
			<div class="row">
				<label class="col-sm-2 control-label scheduler-label">Description</label>
				<div class="col-sm-6">
					 <textarea name="description" rows="3" class="form-control"></textarea>
				</div>
			</div>


		</div>
		<p class="text-center">
			<button class="btn btn-primary" type="submit">Create Task Schedule</button>
		</p>
	</form>
</div>
