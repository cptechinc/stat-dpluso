<?php
	abstract class TableScreenFormatter extends TableScreenMaker {
		protected $formatterfieldsfile = ''; 
		protected $formatter = false; // WILL BE JSON DECODED ARRAY
		protected $tableblueprint = false; // WILL BE ARRAY
		protected $source;
		protected $datetypes = array('m/d/y' => 'MM/DD/YY', 'm/d/Y' => 'MM/DD/YYYY', 'm/d' => 'MM/DD', 'm/Y' => 'MM/YYYY');
		
		/* =============================================================
           CONSTRUCTOR AND SETTER FUNCTIONS
       ============================================================ */
		public function __construct($sessionID) {
			parent::__construct($sessionID);
			$this->load_fields();
			$this->load_formatter();
			$this->generate_tableblueprint();
		}
		
		public function set_debug($debug) {
			$this->debug = $debug;
			$this->load_filepath();
		}
		
		public function set_userid($userID) {
			$this->userID = $userID;
		}
		
		/* =============================================================
          GETTER FUNCTIONS
       ============================================================ */
		public function get_formatter() {
			if (!$this->formatter) {
                $this->load_formatter();
            }
            return $this->formatter;
		}
		
		public function get_defaultformattercolumn() {
			return array(
				"line" => 0,
				"column"=> 0,
				"col-length"=> 0,
				"label"=> "Label",
				"before-decimal"=> false,
				"after-decimal"=> false,
				"date-format"=> false
			);
		}
		
		/* =============================================================
          PUBLIC FUNCTIONS
       	============================================================ */
			public function generate_formatterfrominput(Processwire\WireInput $input) {
				$this->formatter = false;
				$postarray = $table = array('cols' => 0);
				$tablesections = array_keys($this->fields['data']);
				
				if ($input->post->user) {
					$userID = $input->post->text('user');
					$this->set_userid($userID);
				} else {
					$this->set_userid(Processwire\wire('user')->loginid);
				}
				
				foreach ($tablesections as $tablesection) {
					$postarray[$tablesection] = array('rows' => 0, 'columns' => array());
					$table[$tablesection] = array('maxrows' => 0, 'rows' => array());
					
					foreach (array_keys($this->fields['data'][$tablesection]) as $column) {
						$postcolumn = str_replace(' ', '', $column);
						$linenumber = $input->post->int($postcolumn.'-line');
						$length = $input->post->int($postcolumn.'-length');
						$colnumber = $input->post->int($postcolumn.'-column');
						$label = $input->post->text($postcolumn.'-label');
						$dateformat = $beforedecimal = $afterdecimal = false;
						
						if ($this->fields['data'][$tablesection][$column]['type'] == 'D') {
							$dateformat = $input->post->text($postcolumn.'-date-format');
						} elseif ($this->fields['data'][$tablesection][$column]['type'] == 'N') {
							$beforedecimal = $input->post->int($postcolumn.'-before-decimal');
							$afterdecimal = $input->post->int($postcolumn.'-after-decimal');
						}
						
						$postarray[$tablesection]['columns'][$column] = array(
							'line' => $linenumber, 
							'column' => $colnumber, 
							'col-length' => $length, 
							'label' => $label, 
							'before-decimal' => $beforedecimal, 
							'after-decimal' => $afterdecimal, 
							'date-format' => $dateformat
						);
					}
					
					foreach ($postarray[$tablesection]['columns'] as $column) {
						if ($column['line'] > $postarray[$tablesection]['rows']) {
							$postarray[$tablesection]['rows'] = $column['line'];
						}
					}
					
					for ($i = 1; $i < ($postarray[$tablesection]['rows'] + 1); $i++) {
						$table[$tablesection]['rows'][$i] = array('columns' => array());
						
						foreach ($postarray[$tablesection]['columns'] as $column) {
							if ($column['line'] == $i) {
								$table[$tablesection]['rows'][$i]['columns'][$column['column']] = $column;
							}
						}
					}
					
					foreach ($table[$tablesection]['rows'] as $row) {
						$columncount = 0;
						$maxcolumn = 0;
						foreach ($row['columns'] as $column) {
							$columncount += $column['col-length'];
							$maxcolumn = $column['column'] > $maxcolumn ? $column['column'] : $maxcolumn;
						}
						$columncount = ($maxcolumn > $columncount) ? $maxcolumn : $columncount;
						$postarray['cols'] = ($columncount > $postarray['cols']) ? $columncount : $postarray['cols'];
					}
				}
				$this->formatter = $postarray;
				$this->source = 'input';
				$this->generate_tableblueprint();
			}
			
			public function save($debug = false) {
				$userID = Processwire\wire('user')->loginid;
				$userpermission = Processwire\wire('pages')->get('/config/')->allow_userscreenformatter;
				$userpermission = (!empty($userpermission)) ? $userpermission : Processwire\wire('users')->get("name=$userID")->hasPermission('setup-screen-formatter');
				
				if ($this->has_savedformatter()) {
					return $this->update($debug);
				} else {
					return $this->create($debug);
				}
			}
			
			public function save_andrespond() {
				$response = $this->save();
				
				if ($response['success']) {
					$msg = $this->userID == Processwire\wire('user')->loginid ? "Your table ($this->type) configuration has been saved" : "The configuration for $this->userID has been saved";
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
					$msg = $this->userID == Processwire\wire('user')->loginid ? "Your configuration ($this->type) was not able to be saved, you may have not made any discernable changes." : "The configuration for $this->userID was not able to be saved, you may have not made any discernable changes.";
					$json = array (
						'response' => array (
							'error' => true,
							'notifytype' => 'danger',
							'action' => $response['querytype'],
							'message' => $msg,
							'icon' => 'glyphicon glyphicon-warning-sign',
						)
					);
				}
				return $json;
			}
			
			public function can_edit() {
	            $allowed = false;
	            if (Processwire\wire('users')->find("name=".Processwire\wire('user')->loginid)->count) {
	               $allowed = Processwire\wire('users')->get("name=".Processwire\wire('user')->loginid)->hasPermission('setup-screen-formatter');
	            }
				return $allowed;
	        }
			
		/* =============================================================
          INTERNAL FUNCTIONS
       	============================================================ */
 	   protected function load_fields() {
 		   $this->fields = json_decode(file_get_contents(self::$fieldfiledir."$this->formatterfieldsfile.json"), true);
 	   }
	   
		protected function load_formatter() {
			if ($this->does_userhaveformatter()) {
				$this->formatter = getformatter(Processwire\wire('user')->loginid, $this->type, false);
				$this->source = 'database';
			} elseif ($this->does_userhaveformatter('default')) {
				$this->formatter = getformatter('default', $this->type, false);
				$this->source = 'database';
			} else {
				$this->formatter = file_get_contents(Processwire\wire('config')->paths->vendor."cptechinc/dpluso-screen-formatters/src/default/$this->type.json");
				$this->source = 'default';
			}
			$this->formatter = json_decode($this->formatter, true);
		}
		
		protected function does_userhaveformatter($userID = false) {
			$userID = !empty($userID) ? $userID : Processwire\wire('user')->loginid;
			return checkformatterifexists($userID, $this->type, false);
		}
		
        protected function generate_tableblueprint() {
            $tablesections = array_keys($this->fields['data']);
            $table = array('cols' => $this->formatter['cols']);
			
            foreach ($tablesections as $section) {
                $columns = array_keys($this->formatter[$section]['columns']);
				
                $table[$section] = array(
					'maxrows' => $this->formatter[$section]['rows'], 
					'rows' => array()
				);
            
                for ($i = 1; $i < $this->formatter[$section]['rows'] + 1; $i++) {
            		$table[$section]['rows'][$i] = array('columns' => array());
            		foreach ($columns as $column) {
            			if ($this->formatter[$section]['columns'][$column]['line'] == $i) {
            				$col = array(
            					'id' => $column, 
            					'label' => $this->formatter[$section]['columns'][$column]['label'], 
            					'column' => $this->formatter[$section]['columns'][$column]['column'], 
            					'col-length' => $this->formatter[$section]['columns'][$column]['col-length'], 
            					'before-decimal' => $this->formatter[$section]['columns'][$column]['before-decimal'],
            					'after-decimal' => $this->formatter[$section]['columns'][$column]['after-decimal'], 
            					'date-format' => $this->formatter[$section]['columns'][$column]['date-format']
            				 );
            				$table[$section]['rows'][$i]['columns'][$this->formatter[$section]['columns'][$column]['column']] = $col;
            			}
            		}
            	}
            }
            $this->tableblueprint = $table;
        }
		/* =============================================================
			DATABASE FUNCTIONS
		============================================================ */
		public function has_savedformatter() {
			return does_tableformatterexist($this->userID, $this->type);
		}
		
		protected function update($debug = false) {
			return update_formatter($this->userID, $this->type, json_encode($this->formatter), $debug);
		}
		
		protected function create($debug = false) {
			return create_formatter($this->userID, $this->type, json_encode($this->formatter), $debug);
		}
			
}
