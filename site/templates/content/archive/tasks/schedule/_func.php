<?php
function getweekdays() {
    for ($i = 2; $i < 7; $i++) {
        $weekdays [] = date("l",mktime(0,0,0,3,28,2009)+$i * (3600*24));
    }
    return $weekdays;
}

function makecalendarpicker() {
    $table = "<table class='table-condensed table-bordered'>";
    for ($i = 1; $i < 29; $i++) {
        if ($i == 1) {$table .= "<tr>";}
        if ($i < 10) {$show = "0".$i;} else {$show = $i; }
        $table .= "<td><button type='button' class='btn btn-default btn-sm day-number'>".$show."</button></td>";
        if ($i % 7 == 0) { $table .= "</tr><tr>";}
    }
    if ($i % 7 != 0) {$table .= "</tr>";}
    $table .= "</table>";
    return $table;
}

?>
