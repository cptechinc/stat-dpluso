<?php 
	class CustomerTest {
		public function __construct($sessionID, \Purl\Url $url, $config) {
			$this->sessionID = $sessionID;
			$this->redir = new \Purl\Url($url->getUrl());
			$this->redir->path = Processwire\wire('config')->pages->customer.'redir/';
			$this->config = $config;
		}
		
		public function is_custindexloaded($debug = false) {
			return is_custindexloaded($debug);	
		}
		
		public function is_custpermloaded($withuser = false, $debug = false) {
			$userID = $withuser ? $config['userID'] : false;
			return count_custperm($userID, $debug);
		}
	}
