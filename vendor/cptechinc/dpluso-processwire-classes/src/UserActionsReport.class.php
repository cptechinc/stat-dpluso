<?php 
    class UserActionsReport extends UserActionDisplay {
        protected $querylinks = array();
        
        public function __construct(\Purl\Url $pageurl, $querylinks) {
            parent::__construct($pageurl);
            $this->querylinks = $querylinks;
        }
        
        /** 
         * [generate_actionsbytypearray description]
         * @return array that has arrays that are composed of label of action type and how many of that action type are in the DB
         */
        public function generate_actionsbytypearray() {
            $array = array();
            $actiontypes = wire('pages')->get('/activity/')->children('children.count>0');
            
            foreach ($actiontypes as $actiontype) {
                $overridelinks['actiontype'] = $actiontype->name;
                $array[] = array(
                    'label' => ucfirst($actiontype->name), 
                    'value' => intval($this->count_actions(false, $overridelinks))
                );
            }
            return $array;
        }
        
        public function generate_completionarray() {
            return array(
                array(
                    'label' => 'Completed',
                    'value' => intval($this->count_actions(false, array('completed' => 'Y')))
                ),
                array(
                    'label' => 'Incomplete',
                    'value' => intval($this->count_actions(false, array('completed' => ' ')))
                ),
                array(
                    'label' => 'Rescheduled',
                    'value' => intval($this->count_actions(false, array('completed' => 'R')))
                )
            );
        }
        
        public function get_actions($debug = false, $overridelinks = false) {
            
        }
        
        public function count_actions($debug = false, $overridelinks = false) {
            $querylinks = $overridelinks ? array_merge($this->querylinks, $overridelinks) : $this->querylinks;
            if ($debug) {
                return count_useractions($this->userID, $querylinks, $debug);
            } else {
                return count_useractions($this->userID, $querylinks, $debug);
            }
        }
        
        public function generate_filter($column, $filtertype, $value) {
            switch ($filtertype) {
                case 'equals':
                    $filter = generate_equalsfilter($value);
                    break;
                case 'between':
                    $filter = $this->generate_betweenfilter($value);
                    break;
                case 'notequal':
                    $filter = $this->generate_notequalsfilter($value);
                    break;
                case '=':
                    $filter = generate_equalsfilter($value);
                    break;
                case '<-->':
                    $filter = $this->generate_betweenfilter($value);
                    break;
                case '!=':
                    $filter = $this->generate_notequalsfilter($value);
                    break;
                case 'in': 
                    $filter = $this->generate_infilter($value);
                    break;
            }
            return $filter;
        }
        
        public function generate_equalsfilter($value) {
            return '=|'.$this->generate_valuestring($value);
        }
        
        public function generate_notequalsfilter($value) {
            return '!=|'.$this->generate_valuestring($value);
        }
        
        public function generate_infilter($value) {
            return '()|'.$this->generate_valuestring($value);
        }
        
        protected function generate_valuearray($value) {
            $returnvalue = '';
            if (gettype($value) == 'string') {
                if (strpos($value, ',') !== false) {
                    $value = explode(',', $value);
                }
            }
            
            if (gettype($value) == 'array') {
                $returnvalue = implode(',', $value);
            } else {
                $returnvalue = $value;
            }
            
            return $returnvalue;
        }
        
        public function generate_betweenfilter($value) {
            return '<-->|' . $this->generate_valuestring($value);
        }
        
        public function filter($column, $filtertype, $filtervalue) {
            if (array_key_exists($column, $this->querylinks)) {
                $filter = generate_filter($column, $filtertype, $value);
                if (!empty($filter)) {
                    $this->querylinks[$column] = $filter;
                }
            }
        }
        
    }
