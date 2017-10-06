<?php 
function returntaskstable($status) {
    switch ($status) {
        case 'Y':
            $table = 'view_completed_tasks';
            break;
        case 'N':
            $table = 'view_incomplete_tasks';
            break;
        case 'R':
            $table = 'view_rescheduled_tasks';
            break;
        default:
            $table = 'view_incomplete_tasks';
            break;
    }
    return $table;
}
 ?>
