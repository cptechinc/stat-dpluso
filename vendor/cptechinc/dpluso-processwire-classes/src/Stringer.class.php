<?php
    class StringerBell {
        
        function highlight($haystack, $needle) {
            $bootstrap = new Contento();
            if ($this->matches_phone($haystack)) {
                $needle = $this->format_needleforphone($needle);
            }
            $regex = "/(".str_replace('-', '\-?', $needle).")/i";
            $contains = preg_match($regex, $haystack, $matches);
            if ($contains) {
               $highlight = $bootstrap->openandclose('span', 'class=highlight', $matches[0]);
               return preg_replace($regex, $highlight, $haystack);
           } else {
               return $haystack;
           }
        }
        
        function matches_phone($string) {
            $regex = "/\d{3}-?\d{3}-?\d{4}/i";
            return preg_match($regex, $string) ? true : false;
        }
        
        function format_needleforphone($string) {
            $string = str_replace('-', '', $string);
            return sprintf("%s-%s-%s",
              substr($string, 0, 3),
              substr($string, 3, 3),
              substr($string, 6));
        }
    }
