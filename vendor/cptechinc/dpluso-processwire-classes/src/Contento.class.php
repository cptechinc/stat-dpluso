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
        
        public function button($class, $content =  '') {
            return $this->openandclose('button', "class=$class", $content);
        }
        
        public function createalert($type, $msg, $showclose = true) {
            $attributes = "class=alert alert-$type|role=alert";
            $closebutton = $this->openandclose('button', 'class=close|data-dismiss=alert|aria-label=Close', $this->openandclose('span', 'aria-hidden=true', '&times;'));
            $content = ($showclose) ? $closebutton.$msg : $msg;
            return $this->openandclose('div', $attributes, $content);
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
