<?php 
    class JavaScripter {
        public $tabs = 0;
        protected $tab = "\t";
        protected $newline = "\n";
        protected $script = "";
        protected $return = true;
        
        public function __construct($return = true) {
            $this->return = $return;
        }
        
        public function __toString() {
            return $this->script;
        }
        
        public function generate_onready() {
            $this->tabs++;
            $content = $this->generate_tabs() . '$(function() {' . $this->newline;
            $this->tabs++;
            
            if ($this->return) {
                return $content;
            } else {
                $this->script .= $content;
            }
        }
        
        public function line($line) {
            $content = $this->generate_tabs() . $line . $this->newline;
            
            if ($this->return) {
                return $content;
            } else {
                $this->script .= $content;
            }
        }
        
        public function generate_functioncall($function) {
            $content = $this->generate_tabs() . $function . $this->newline;
            $this->tabs++;
            
            if ($this->return) {
                return $content;
            } else {
                $this->script .= $content;
            }
        }
        
        public function close_functioncall() {
            $this->tabs--;
            $content = $this->generate_tabs() . '});' . $this->newline . $this->newline;
            
            if ($this->return) {
                return $content;
            } else {
                $this->script .= $content;
            }
        }
        
        public function generate_tabs() {
            $content = "";
            if ($this->tabs) {
                for ($i = 0; $i < ($this->tabs + 1); $i++) {
                    $content .= "\t";
                }
            }
            return $content;
        }
    }
