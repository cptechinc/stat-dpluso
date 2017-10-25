<?php
    
    class DplusDateTime {
        static $defaultdate = 'm/d/Y';
        static $defaulttime = 'h:i A';
        static $fulltimestring = 'YmdHisu';
        static $shorttimestring = 'Hi';

        static function formatdplustime($time, $formatstring = null, $timestring = null) {
            $formatstring = $formatstring ? $formatstring : self::$defaulttime;
            $timestring =  $timestring ? $timestring : self::$fulltimestring;
            $formatted = DateTime::createFromFormat($timestring, $time);
            return $formatted->format($formatstring);
        }

        static function formatdate($date, $formatstring = null) {
            $formatstring ? $formatstring = $formatstring : $formatstring = self::$defaultdate;
            if (strtotime($date) == strtotime("12/31/1969")) {
                return '';
            } else {
                return date($formatstring, strtotime($date));
            }
        }
    }
 ?>

