<?php 
    interface QuoteDisplayInterface {
        public function get_quotedetails(Order $quote, $debug = false);
    }
