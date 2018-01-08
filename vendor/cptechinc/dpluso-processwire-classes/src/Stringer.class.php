<?php
    class StringerBell {
        /**
         * [highlight description]
         * @param  string $haystack the string to look through
         * @param  string $needle   the word to look for
         * @return string           html string with the $needle highlighted or returns just the string
         */
        function highlight($haystack, $needle) {
            $bootstrap = new Contento();
            if ($this->matches_phone($haystack)) {
                $needle = $this->does_matchphone($needle);
            }
            $regex = "/(".str_replace('-', '\-?', $needle).")/i";
            $contains = preg_match($regex, $haystack, $matches);
            if ($contains) {
               $highlight = $bootstrap->span('class=highlight', $matches[0]);
               return preg_replace($regex, $highlight, $haystack);
           } else {
               return $haystack;
           }
        }
        
        /**
         * Checks to see if string is in a phone format
         * @param  string $string 
         * @return bool         
         */
        function does_matchphone($string) {
            $regex = "/\d{3}-?\d{3}-?\d{4}/i";
            return preg_match($regex, $string) ? true : false;
        }
        
        /**
         * Takes a String and formats into the phone format
         * @param  string $string 
         * @return string formatted phone string         
         */
        function format_needleforphone($string) {
            $string = str_replace('-', '', $string);
            return sprintf("%s-%s-%s",
              substr($string, 0, 3),
              substr($string, 3, 3),
              substr($string, 6));
        }
    }
