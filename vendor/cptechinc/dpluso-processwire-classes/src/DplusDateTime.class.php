<?php
    class DplusDateTime {
        static $defaultdate = 'm/d/Y';
        static $defaulttime = 'h:i A';
        static $fulltimestring = 'YmdHisu';
        static $shorttimestring = 'Hi';
        
        /**
         * Formats time
         * @param  string $time         time in whatever format provided
         * @param  string $formatstring the format in which time should be formatted to
         * @param  string $timestring   the format which the final format should be
         * @return string               formatted result
         */
        static function formatdplustime($time, $formatstring = null, $timestring = null) {
            $formatstring = $formatstring ? $formatstring : self::$defaulttime;
            $timestring =  $timestring ? $timestring : self::$fulltimestring;
            $formatted = DateTime::createFromFormat($timestring, $time);
            return $formatted->format($formatstring);
        }
        
        /**
         * Formats Date
         * @param  string $date         date in whatever format provided
         * @param  string $formatstring the format in which time should be formatted to
         * @return string               formatted result
         */
        static function formatdate($date, $formatstring = null) {
            $formatstring ? $formatstring = $formatstring : $formatstring = self::$defaultdate;
            if (strtotime($date) == strtotime("12/31/1969")) {
                return '';
            } else {
                return date($formatstring, strtotime($date));
            }
        }
    }
