<?php
    $message = "Create a task schedule for {replace} ";
    $title = createmessage($message, $custID, $shipID, $contactID, $taskID, $noteID, $ordn, $qnbr);
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
        <div class="row">
            <div class="col-xs-12 form-group">
                <label for="">Start Date</label>
                <div class="input-group date" style="width:180px;">
                   	<?php $name = 'startdate'; $value = date('m/d/Y'); ?>
					<?php include $config->paths->content."common/date-picker.php"; ?>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-6 form-group">
                <label for="">Description</label> <textarea name="description" rows="3" class="form-control"></textarea>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-2 col-xs-6 form-group">
                <label for="">Repeats</label>
                <select class="form-control repeat-type" name="repeat">
                    <option value="n/a">Choose</option>
                    <?php foreach ($taskfrequencytypes as $type) : ?>
                         <option value="<?= $type['type']; ?>"><?= ucfirst($type['description']); ?></option>
                    <?php endforeach; ?>
                </select>
                <p class="repeat-description"></p>
            </div>
            <div class="col-sm-2 col-xs-6 form-group hidden">
                <label class="interval-label">Interval</label>
                <input type="text" class="form-control every" name="interval">
            </div>
            <div class="col-sm-2 col-xs-6 form-group hidden">
                <label>Week of the Month</label>
               	<select name="week-interval"class="form-control every-week">
               		<option value="1">1</option>
               		<option value="2">2</option>
               		<option value="3">3</option>
               		<option value="4">4</option>
               	</select>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-3 form-group weekdays hidden">
                <label>Weekdays</label>
                <select name="" id="" class="form-control week-days-select">
                    <option value="n/a">Choose</option>
                    <?php $weekdays = getweekdays(); ?>
                    <?php foreach ($weekdays as $weekday) : ?>
                        <option value="<?= $weekday; ?>"><?= $weekday; ?></option>
                    <?php endforeach;?>
                </select>
            </div>
            <div class="col-sm-5 form-group calendardays hidden">
                <label>Calendar Days</label> <?php echo makecalendarpicker(); ?>
            </div>
            <div class="col-sm-6">
                <div class="hidden">
                    <label>ON</label> <input type="text" class="form-control repeatson" name="repeats-on">
                </div>
            </div>

        </div>
        <h2><span class="help-block schedule-desc"></span></h2>
        <p class="text-center">
            <button class="btn btn-primary" type="submit">Create Task Schedule</button>
        </p>
    </form>

</div>

