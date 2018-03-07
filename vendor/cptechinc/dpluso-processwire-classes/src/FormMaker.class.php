<?php 
    class FormMaker {
        use ThrowErrorTrait;
        
        private $formstring = '';
        private static $count = 0;
        private $openform;
        public $bootstrap = false;
        /* =============================================================
			CONSTRUCTOR FUNCTIONS 
		============================================================ */
        public function __construct($attr = '', $openform = true) {
            self::$count++;
            $this->bootstrap = new Contento();
            $this->formstring = $this->indent() . $openform ? $this->bootstrap->open('form', $attr) : '';
            $this->openform = $openform;
        }
        
        /* =============================================================
			GETTER FUNCTIONS 
		============================================================ */
        public function __call($name, $args){
            if (in_array($name, $this->bootstrap->closeable)) {
                if (!$args[1]) {
                    $this->formstring .= $this->bootstrap->open($name, $args[0]); // OPEN ONLY
                } else {
                    $this->formstring .= $this->bootstrap->openandclose($name, $args[0], $args[1]); // CLOSE ONLY
                }
            } elseif (in_array($name, $this->bootstrap->emptytags)) {
                $this->formstring .= $this->bootstrap->open($name, $args[0]);    
            } else {
                $this->error("This element $name is not defined to be called as a closing or open ended element");
                return false;
            }
        }
        
        /* =============================================================
			CLASS FUNCTIONS 
		============================================================ */
        public function input($attr = '') {
            $this->formstring .= $this->indent() . $this->bootstrap->input($attr);
        }
        
        public function select($attr = '', array $keyvalues, $selectvalue = null) {
            $this->formstring .= $this->indent() . $this->bootstrap->select($attr, $keyvalues, $selectvalue);
        }
        
        public function button($attr = '', $content) {
            $this->formstring .= $this->indent() . $this->bootstrap->button($attr, $content);
        }
        
        public function add($str) {
            $this->formstring .= $str;
        }
        
        public function close($element) {
            $this->formstring .= $this->bootstrap->close($element);
        }
        
        public function finish() {
			if ($this->openform) {
				$this->formstring .= $this->bootstrap->close('form');
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
