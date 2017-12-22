<?php 
    class Contento {
        use AttributeParser;
        protected $opentag = false;
        protected $closeable = array(
            'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'p', 'span', 'i', 'b', 'strong', 'code', 'pre',
            'div', 'nav', 'ol', 'ul', 'li', 'button',
            'table', 'tr', 'td', 'th', 'thead', 'tbody', 'tfoot',
            'textarea', 'option', 'label', 'a'
        );
        
        protected $emptytags = array(
            'input', 'img', 'br'
        );
        
        public function __call($name, $args) {
            if (!method_exists($this, $name)) {
                if (in_array($name, $this->closeable)) {
                    if (!$args[1]) {
                        return $this->open($name, $args[0]); // OPEN ONLY
                    }
                    return $this->openandclose($name, $args[0], $args[1]); // CLOSE ONLY
                } elseif (in_array($name, $this->emptytags)) {
                    $this->opentag = $name;
                    return $this->open($name, $args[0]);    
                } else {
                    $this->error("This element $name is not defined to be called as a closing or open ended element");
                    return false;
                }
            }
            
        }
        
        public function __get($property) {
            if (property_exists($this, $property) !== true) {
               $this->error("This property ($property) does not exist");
               return false;
           }
           
           $method = "get_{$property}";
           if (method_exists($this, $method)) {
               return $this->$method();
           } elseif (property_exists($this, $property)) {
               return $this->$property;
           } else {
               $this->error("This property ($property) is not accessible");
               return false;
           }
        }
        
        public function open($element, $attributes) {
            $attributes = $this->attributes($attributes);
            return "<$element $attributes>";
        }
        
        public function close($element = false) {
            if (!$element) {
                if ($this->opentag) {
                    $element = $this->opentag;
                    $this->opentag = false;
                }
            }
            return "</$element>";
        }
        
        public function openandclose($element, $attributes, $content) {
            return $this->open($element, $attributes) . $content . $this->close($element);
        }
        
        public function createicon($class, $content = '') {
            return $this->openandclose('i', "class=$class|aria-hidden=true", $content);
        }
        
        public function ariahidden($content) {
            return $this->openandclose('span', 'aria-hidden=true', $content);
        }
        
        public function sronly($content) {
            return $this->openandclose('span', "class=sr-only", $content);
        }
        
        public function span($attributes, $content =  '') {
            return $this->openandclose('span', $attributes, $content);
        }
        
        public function select($attr = '', array $keyvalues, $selectvalue = null) {
            $str = $this->open('select', $attr);
            
            foreach ($keyvalues as $key => $value) {
                $optionattr = "value=$key";
                $optionattr .= ($key == $selectvalue) ? "|selected=noparam" : '';
                $str .= $this->openandclose('option', $optionattr, $value);
            }
            $str .= $this->close('select');
            return $str;
        }
        
        public function createalert($type, $msg, $showclose = true) {
            $attributes = "class=alert alert-$type|role=alert";
            $closebutton = $this->openandclose('button', 'class=close|data-dismiss=alert|aria-label=Close', $this->span('aria-hidden=true', '&times;'));
            $content = ($showclose) ? $closebutton.$msg : $msg;
            return $this->openandclose('div', $attributes, $content);
    	}

    	public function makeprintlink($link, $msg) {
            $attributes = "href=$link|class=h4|target=_blank";
            $content = '<i class="glyphicon glyphicon-print" aria-hidden="true"></i> ' . $msg;
            return $this->openandclose('a', $attributes, $content);
    	}
        
        public function datepicker($name, $value, $init = false) {
            $str = $this->open('div', 'class=input-group datepicker');
                $str .= $this->open('input', "type=text|class=form-control input-sm date-input|name=$name|value=$value");
                $str = $this->open('div', 'input-group-btn');
                    $btncontent = $this->createicon('glyphicon glyphicon-calendar').$this->sronly('Toggle Calendar');
                    $str .= $this->button('type=button|class=btn btn-sm btn-default dropdown-toggle|data-toggle=dropdown', $btncontent);
                    $str .= $this->open('div', 'class=dropdown-menu dropdown-menu-right datepicker-calendar-wrapper|role=menu');
                    $str .= $this->generate_datepickercalendar();
                    
                    $str .= $this->close('div'); //dropdown-menu dropdown-menu-right datepicker-calendar-wrappe
                $str .= $this->close('div');
            $str .= $this->close('div');
        }
        
        public function generate_datepickercalendar() {
            $str = $this->open('div', 'class=datepicker-calendar');
                $str = $this->open('div', 'class=datepicker-calendar-header');
                    $str .= $this->button('type=button|class=prev', $this->createicon('glyphicon glyphicon-chevron-left').$this->sronly('Previous Month'));
                    $str .= $this->button('type=button|class=next', $this->createicon('glyphicon glyphicon-chevron-right').$this->sronly('Next Month'));
                    $str.= $this->open('button', 'type=button|class=title');
                        $str.= $this->open('span', 'class=month');
                            for ($i = 0; $i < 12; $i++) {
                                $this->span("data-month=$i", date('F', mktime(0, 0, 0, ($i + 1), 10)));
                            }
                        $str .= $this->close('span');
                        $str .= $this->span('class=year');
                    $str .= $this->close('button');
                $str .= $this->close('div'); // datepicker-calendar-header
                $str .= $this->open('table', 'class=datepicker-calendar-days');
                    $str .= $this->open('thead', '');
                        $str .= $this->open('tr', '');
                            $str .= $this->openandclose('th','','Su');
                            $str .= $this->openandclose('th','','Mo');
                            $str .= $this->openandclose('th','','Tu');
                            $str .= $this->openandclose('th','','We');
                            $str .= $this->openandclose('th','','Th');
                            $str .= $this->openandclose('th','','Fr');
                            $str .= $this->openandclose('th','','Sa');
                        $str .= $this->close('tr');
                    $str .= $this->close('thead');
                    $str .= $this->openandclose('tbody','','');
                $str .= $this->close('table');
                $str .= $this->openandclose('div', 'class=datepicker-calendar-footer', $this->button('type=button|class=date-picker-today', 'Today'));
            $str .= $this->close('div'); // datepicker-calendar
        }
        
        protected function generate_datepickerwheels() {
            $str = $this->open('div', 'class=datepicker-wheels|aria-hidden=true');
                $str .= $this->open('div', 'class=datepicker-wheels-month');
                    $str .= $this->openandclose('h2', 'class=header', 'Month');
                $str.= $this->close('div');
            $str.= $this->close('div');
        }
        
        public function indent() {
            return '    ';
        }
        
        protected function error($error, $level = E_WARNING) {
			$error = (strpos($error, 'DPLUSO [CONTENTO]: ') !== 0 ? 'DPLUSO [CONTENTO]: ' . $error : $error);
			trigger_error($error, $level);
			return;
		}
    }
?>
