<?php 
    class Contento {
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
        
        
        
        /**
    	 * Takes a string of attributes and parses it out by a delimiter (|)
    	 * @param  [type] $vars string of attributes separated by | 
    	 * @return [type]       string of atrributes and values like class=""
    	 */
    	private function attributes($vars) {
    		$put = array();
    		if (!empty($vars)) {
    			$values = explode('|', $vars);
    			foreach ($values as $value) {
    				$pieces = explode('=', $value);
    				$put[array_shift($pieces)] = implode('=', $pieces);
    			}
    		}
    		$attributes = '';
    		if (!empty($put)) {
    			foreach ($put as $key => $value) {
                    $attributes .= ' ' . $key . '="' . $value . '"';
    			}
    		}
    		return $attributes;
    	}
    	
    	/** 
    	 * Makes a new line and adds four spaces to format a string in html
    	 * @return string new line and four spaces
    	 */
    	private function indent() {
    		$indent = "\n";
    		for ($i = 0; $i < self::$count; $i++) {
    			$indent .= '  ';
    		}
    		return $indent;
    	}
    }
?>
