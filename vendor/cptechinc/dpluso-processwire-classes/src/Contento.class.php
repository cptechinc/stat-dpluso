<?php 
    class Contento {
        use AttributeParser;
        use ThrowErrorTrait;
        
        protected $opentag = false;
        protected $closeable = array(
            'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'p', 'span', 'i', 'b', 'strong', 'code', 'pre',
            'div', 'nav', 'ol', 'ul', 'li', 'button',
            'table', 'tr', 'td', 'th', 'thead', 'tbody', 'tfoot',
            'textarea', 'option', 'label', 'a', 'form'
        );
        protected $emptytags = array(
            'input', 'img', 'br'
        );
        
        /* =============================================================
 		   GETTER FUNCTIONS 
 	   ============================================================ */
       /**
        * If a property is not accessible then try to give them the property through
        * a already defined method or to give them the property value
        * @param  string $property property name
        * @return 
        *     1. Value returned in value call
        *     2. Returns the value of the property_exists
        *     3. Throw Error
        */
        public function __get($property) {
           $method = "get_{$property}";
           if (method_exists($this, $method)) {
               return $this->$method();
           } elseif (property_exists($this, $property)) {
               return $this->$property;
           } else {
               $this->error("This property ($property) does not exist");
               return false;
           }
        }
        
        /**
         * [__call description]
         * @param  string $name function name
         * @param  array $args array of arguments
         * @return 
         *         If 1 argument then only use the open function
         *         If has more than one argument then use open and close with argument 0 as the attributes and name as the element type
         *         If function name is a empty tag then use the open function
         *         else throw Error
         */
        public function __call($name, $args) {
            if (!method_exists($this, $name)) {
                if (in_array($name, $this->closeable)) {
                    if (!isset($args[1])) {
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
        
        /* =============================================================
 		   CLASS FUNCTIONS 
 	   ============================================================ */
       /**
        * Creates a non closing element tag
        * @param  string $element    element type
        * @param  string $attributes attritubutes with equal signs and divided by |
        * @return string element tag
        */
        public function open($element, $attributes) {
            $attributes = trim($this->attributes($attributes));
            return empty($attributes) ? "<$element>" : "<$element $attributes>";
        }
        
        /**
         * returns a closing element tag
         * @param  bool $element element type
         * @return string element tag
         */
        public function close($element = false) {
            if (!$element) {
                if ($this->opentag) {
                    $element = $this->opentag;
                    $this->opentag = false;
                }
            }
            return "</$element>";
        }
        
        /**
         * Returns an opening tag with the attributes then its 
         * @param  string  $element    element type
         * @param  string  $attributes $attributes attritubutes with equal signs and divided by |
         * @param  string  $content    
         * @return string             Element open and closing tag and also with the content
         */
        public function openandclose($element, $attributes, $content) {
            return $this->open($element, $attributes) . $content . $this->close($element);
        }
        
        /**
         * Makes an aria-hidden icon that can accept icons that need content
         * @param  string $class   class needed for the icon
         * @param  string $content optional 
         * @return string         <i class="[class]" aria-hidden="true">[content]</i>
         */
        public function createicon($class, $content = '') {
            return $this->openandclose('i', "class=$class|aria-hidden=true", $content);
        }
        /**
         * Creates an aria-hidden span class
         * @param  string $content 
         * @return string          <span aria-hidden="true">[content]</span>
         */
        public function ariahidden($content) {
            return $this->openandclose('span', 'aria-hidden=true', $content);
        }
        
        /**
         * [sronly description]
         * @param  [type] $content 
         * @return        <span class="sr-only">[content]</span>
         */
        public function sronly($content) {
            return $this->openandclose('span', "class=sr-only", $content);
        }
        
        /**
         * [span description]
         * @param  string $attributes [description]
         * @param  string $content    attritubutes with equal signs and divided by |
         * @return string             <span [attributes]>[content]</span>
         */
        public function span($attributes, $content =  '') {
            return $this->openandclose('span', $attributes, $content);
        }
        
        /**
         * Creates Select
         * @param  string $attr        attritubutes with equal signs and divided by |
         * @param  array  $keyvalues   array with the for the option value and label
         * @param  string or int $selectvalue The option that is supposed to be selected
         * @return string              
         */
        public function select($attr = '', array $keyvalues, $selectvalue = null) {
            $str = $this->open('select', $attr);
            
            foreach ($keyvalues as $key => $value) {
                $optionattr = "value=$key";
                $optionattr .= ($key == $selectvalue) ? "|selected=noparam" : '';
                $str .= $this->option($optionattr, $value);
            }
            $str .= $this->close('select');
            return $str;
        }
        
        /**
         * Creates Bootstrap Alert Panel
         * @param  string  $type      alert type Ex. info|warning|danger|success
         * @param  string  $msg       Message to display in the body
         * @param  bool $showclose To display the close button
         * @return string             
         */
        public function createalert($type, $msg, $showclose = true) {
            $attributes = "class=alert alert-$type|role=alert";
            $closebutton = $this->openandclose('button', 'class=close|data-dismiss=alert|aria-label=Close', $this->span('aria-hidden=true', '&times;'));
            $content = ($showclose) ? $closebutton.$msg : $msg;
            return $this->openandclose('div', $attributes, $content);
    	}
        
        /**
         * Make HTML link to the printable page
         * @param  string $href URL to the printable page
         * @param  string $msg  Message to display in the link
         * @return string       <a href="[href]"><i class="glyphicon glyphicon-print" aria-hidden="true"></i> [msg]</a>
         */
    	public function makeprintlink($href, $msg) {
            $attributes = "href=$href|class=h4|target=_blank";
            $content = $this->createicon('glyphicon glyphicon-print') .' '. $msg;
            return $this->openandclose('a', $attributes, $content);
    	}
        
        public function datepicker($name, $value, $init = false) {
            $str = $this->open('div', 'class=input-group datepicker');
                $str .= $this->open('input', "type=text|class=form-control input-sm date-input|name=$name|value=$value");
                $str .= $this->open('div', 'class=input-group-btn');
                    $btncontent = $this->createicon('glyphicon glyphicon-calendar').$this->sronly('Toggle Calendar');
                    $str .= $this->button('type=button|class=btn btn-sm btn-default dropdown-toggle|data-toggle=dropdown', $btncontent);
                    $str .= $this->open('div', 'class=dropdown-menu dropdown-menu-right datepicker-calendar-wrapper|role=menu');
                    $str .= $this->generate_datepickercalendar();
                    $str .= $this->close('div'); //dropdown-menu dropdown-menu-right datepicker-calendar-wrappe
                $str .= $this->close('div');
            $str .= $this->close('div');
            return $str;
        }
        
        public function generate_datepickercalendar() {
            $str = $this->open('div', 'class=datepicker-calendar');
                $str .= $this->open('div', 'class=datepicker-calendar-header');
                    $str .= $this->button('type=button|class=prev', $this->createicon('glyphicon glyphicon-chevron-left').$this->sronly('Previous Month'));
                    $str .= $this->button('type=button|class=next', $this->createicon('glyphicon glyphicon-chevron-right').$this->sronly('Next Month'));
                    $str.= $this->open('button', 'type=button|class=title');
                        $str.= $this->open('span', 'class=month');
                            for ($i = 0; $i < 12; $i++) {
                                $str .= $this->span("data-month=$i", date('F', mktime(0, 0, 0, ($i + 1), 10)));
                            }
                        $str .= $this->close('span') . '&nbsp;';
                        $str .= $this->span('class=year');
                    $str .= $this->close('button');
                $str .= $this->close('div'); // datepicker-calendar-header
                $str .= $this->generate_weektable();
                $str .= $this->openandclose('div', 'class=datepicker-calendar-footer', $this->button('type=button|class=date-picker-today', 'Today'));
            $str .= $this->close('div'); // datepicker-calendar
            $str .= $this->generate_datepickerwheels();
            return $str;
        }
        
        protected function generate_weektable() {
            $tb = new Table('class=datepicker-calendar-days');
            $tb->tablesection('thead');
                $tb->tr();
                $tb->th('', 'Su')->th('','Mo')->th('','Tu')->th('','We')->th('','Th')->th('','Fr')->th('','Sa');
            $tb->closetablesection('thead');
            return $tb->close();
        }
        
        protected function generate_datepickerwheels() {
            $str = $this->open('div', 'class=datepicker-wheels|aria-hidden=true');
                $str .= $this->open('div', 'class=datepicker-wheels-month');
                    $str .= $this->openandclose('h2', 'class=header', 'Month');
                    $str .= $this->open('ul', '');
                    for ($i = 0; $i < 12; $i++) {
                        $str .= $this->li("data-month=$i", $this->button('type=button', date('F', mktime(0, 0, 0, ($i + 1), 10))));
                    }
                    $str .= $this->close('ul');
                $str.= $this->close('div');
                
                $str .= $this->open('div', 'class=datepicker-wheels-year');
                    $str .= $this->h2('class=header', 'Year');
                    $str .= $this->ul('', '');
                $str.= $this->close('div');
                
                $str .= $this->open('div', 'class=datepicker-wheels-footer clearfix');
                    $str .= $this->button('class=btn datepicker-wheels-back', $this->createicon('glyphicon glyphicon-arrow-left') . $this->sronly('Return to Calendar'));
                    $str .= $this->button('class=btn datepicker-wheels-select', 'Select ' . $this->sronly('Return to Calendar'));
                $str.= $this->close('div');
            $str.= $this->close('div');
            return $str;
        }
		
		public function generate_attributes($attr) {
			return $this->attributes($attr);
		}
        
        public function indent() {
            return '    ';
        }
    }
