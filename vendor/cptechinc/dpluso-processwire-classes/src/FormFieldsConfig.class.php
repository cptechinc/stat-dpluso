<?php 

    class FormFieldsConfig {
		use ThrowErrorTrait;
		
        protected $formtype;
        protected $fields = false;
        protected $allowedtypes = array('sales-order', 'quote');
        public static $filedir = false;
        
        
        public function __construct($formtype) {
            $this->formtype = $formtype;
            $this->init($formtype);
        }
		
		/* =============================================================
    		GETTER FUNCTIONS
    	============================================================ */
        public function __get($property) {
			if (property_exists($this, $property) !== true) {
				$this->error("This property ($property) does not exist");
				return false;
			}
			$method = "get_{$property}";
			if (method_exists($this, $method)) {
				return $this->$method();
			} else {
				return $this->$property;
			}
		}
        
        /* =============================================================
    		CLASS FUNCTIONS
    	============================================================ */
		/**
		 * Loads the fields from the config whether from the database or the 
		 * default file
		 * @param  string $formtype type of form ex sales-order | quote
		 */
        protected function init($formtype) {
            if (!in_array($formtype, $this->allowedtypes)) {
                $this->error("$formtype is not a valid form config");
                return false;
            } else {
                if (does_customerconfigexist($formtype)) {
                    $this->fields = json_decode(get_customerconfig($formtype), true);
                } else {
                    $this->load_file();
                }
            }
        }
        
		/**
		 * 
		 * @return [type] [description]
		 */
        public function load_file() {
            if (file_exists(self::$filedir.$this->formtype."-form-fields.json")) {
                $this->fields = json_decode(file_get_contents(self::$filedir.$this->formtype."-form-fields.json"), true);
            } else {
                $this->error("Can't find default config for this formtype.");
            }
		}
		
		public static function set_defaultconfigdirectory($dir) {
			self::$filedir = $dir;
		}
        
        public function generate_showrequired($key) {
            return $this->fields['fields'][$key]['required'] ? 'checked' : '';
        }
        
        public function generate_showrequiredclass($key) {
            return $this->fields['fields'][$key]['required'] ? 'required' : '';
        }
        
        public function generate_asterisk($key) {
            return $this->fields['fields'][$key]['required'] ? '&nbsp;<b class="text-danger">*</b>' : '';
        }
        
        
        public function generate_configfrominput(WireInput $input) {
            foreach ($this->fields['fields'] as $key => $field) {
                $this->fields['fields'][$key]['label'] = $input->post->text("$key-label");
                $this->fields['fields'][$key]['before-decimal'] = strlen($input->post->text("$key-before-decimal")) ? $input->post->text("$key-before-decimal") : false;
                $this->fields['fields'][$key]['after-decimal'] = strlen($input->post->text("$key-after-decimal")) ? $input->post->text("$key-after-decimal") : false;
                $this->fields['fields'][$key]['required'] = strlen($input->post->text("$key-required")) ? true : false;
            }
        }
		
		/* =============================================================
    		CRUD FUNCTIONS
    	============================================================ */
        public function save() {
            if (does_customerconfigexist($this->formtype)) {
                return update_customerconfig($this->formtype, json_encode($this->fields), $debug = false);
            } else {
                return create_customerconfig($this->formtype, json_encode($this->fields), $debug = false);
            }
        }
        
        public function save_andrespond() {
            $response = $this->save();
            if ($response['success']) {
                $msg = "Your form ($this->formtype) configuration has been saved";
                $json = array (
                    'response' => array (
                        'error' => false,
                        'notifytype' => 'success',
                        'action' => $response['querytype'],
                        'message' => $msg,
                        'icon' => 'glyphicon glyphicon-floppy-disk',
                    )
                );
            } else {
                $msg = "Your configuration ($this->formtype) was not able to be saved, you may have not made any discernable changes.";
                $json = array (
                    'response' => array (
                        'error' => true,
                        'notifytype' => 'danger',
                        'action' => $response['querytype'],
                        'message' => $msg,
                        'icon' => 'glyphicon glyphicon-warning-sign',
                        'sql' => $response['sql']
                    )
                );
            }
            return $json;
        }
    }
