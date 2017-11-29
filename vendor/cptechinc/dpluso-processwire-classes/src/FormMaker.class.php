<?php 
    class FormMaker extends Contento {
        use AttributeParser;
        private $formstring = '';
        private static $count = 0;
        
        function __construct($attr = '') {
            self::$count++;
            $this->formstring = $this->indent() . $this->open('form', $attr);
        }
        
        public function input($attr = '') {
            $this->formstring .= $this->indent() . $this->open('input', $attr);
        }
        
        public function select($attr = '', array $keyvalues, string $selectvalue = null) {
            $this->formstring .= $this->indent() . $this->open('select', $attr);
            
            foreach ($keyvalues as $key => $value) {
                $optionattr = ($key == $selectvalue) ? $optionattr = "selected=noparam" : '';
                $this->formstring .= $this->indent() . $this->openandclose('option', $optionattr, $value);
            }
            
            $this->formstring .= $this->close('select');
        }
        
        public function button($attr = '', $content) {
            $this->formstring .= $this->indent() . $this->openandclose('button', $attr, $content);
        } 
        
        public function finish() {
            if (self::$count < 0) {
                self::$count--;
                $this->formstring .= $this->close('form');
            }
            return $this->formstring;
        }
        
        public function _toString() {
            return $this->finish();
        }
        
        
        /** 
    	 * Makes a new line and adds four spaces to format a string in html
    	 * @return string new line and four spaces
    	 */
    	protected function indent() {
    		$indent = "\n";
    		for ($i = 0; $i < self::$count; $i++) {
    			$indent .= '  ';
    		}
    		return $indent;
    	}
    }
