<?php
	/**
	 * Class That converts dates and times and defaults to converting Dates and Times from the DPlus Cobol System
	 */
	class DplusDateTime {
		static $defaultdate = 'm/d/Y';
		static $defaulttime = 'h:i A';
		static $fulltimestring = 'YmdHisu';
		static $shorttimestring = 'Hi';
		
	/**
	 * [format_dplustime description]
	 * @param  string $time          time string ex 16063372
	 * @param  string $currentformat format the time is in, default is Hi
	 * @param  string $desiredformat desired format, default is h:i A -> 10:19 AM
	 * @return string                Time Formatted
	 */
		public static function format_dplustime($time, $currentformat = 'Hi', $desiredformat = 'h:i A') {
			$formatted = DateTime::createFromFormat($currentformat, $time);
			return $formatted->format($desiredformat);
		}
		
		/**
		 * Formats Date
		 * @param  string $date         date in whatever format provided
		 * @param  string $formatstring the format in which time should be formatted to defualt is m/d/Y
		 * @return string               formatted result
		 */
		public static function format_date($date, $formatstring = 'm/d/Y') {
	return (strtotime($date) == strtotime("12/31/1969")) ? '' : date($formatstring, strtotime($date));
		}
	}
