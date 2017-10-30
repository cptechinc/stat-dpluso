<?php 
    class Contento {
        use AttributeParser;
        
        public function open($element, $attributes) {
            $attributes = $this->attributes($attributes);
            return "<$element $attributes>";
        }
        
        public function close($element) {
            return "</$element>";
        }
        
        public function openandclose($element, $attributes, $content) {
            return $this->open($element, $attributes) . $content . $this->close($element);
        }
        
        public function createicon($class, $content = '') {
            return $this->openandclose('i', "class=$class|aria-hidden=true", $content);
        }
        
        public function createalert($type, $msg) {
            $attributes = "class=alert alert-$type|role=alert";
            return $this->openandclose('div', $attributes, $msg);
    	}

    	public function makeprintlink($link, $msg) {
            $attributes = "href=$link|class=h4|target=_blank";
            $content = '<i class="glyphicon glyphicon-print" aria-hidden="true"></i> ' . $msg;
            return $this->openandclose('a', $attributes, $content);
    	}
        
        protected function indent() {
            return '    ';
        }
    }
?>
