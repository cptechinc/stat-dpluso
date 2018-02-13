<?php 
    class DplusEmailer {
        use ThrowErrorTrait;
        protected $user;
        protected $hashtml = false;
        protected $hasfile;
        public static $filedirectory;
        protected $subject;
        protected $emailto = false; // Key Value array that is Email => Recipient Name
        protected $replyto = false; // Key Value array that is Email => Reply Name
        protected $emailfrom = false; // Key Value array that is Email => Email From Name
        protected $bcc = false; // Key Value array that is Recipient => Email Name
        protected $cc = false; // Key Value array that is Recipient => Email Name
        protected $body;
        protected $file = false;
        
        function __construct($loginID) {
            $this->user = LogmUser::load($loginID);
            if (!$this->user) {
                $this->error("Could Not Find User with loginid of $userID");
                return false;
            }
            $this->replyto = array($this->user->email => $this->user->name);
            $this->emailfrom = array($this->user->email => $this->user->name);
        }
        
        /* =============================================================
 		   GETTERS
 	   ============================================================ */
        public function __get($property) {
            $method = "get_{$property}";
            if (method_exists($this, $method)) {
                return $this->$method();
            } elseif (property_exists($this, $property)) {
                return $this->$property;
            } else {
                $this->error("This property ($property) does not exist");
            }
        }
        
        public function get_filedirectory() {
            return $this->filedirectory;
        }
        
        /* =============================================================
           SETTERS
        ============================================================ */
        public function __set($property, $data) {
            $method = "set_{$property}";
            if (method_exists($this, $method)) {
                return $this->$method($data);
            } else {
                $this->error("This property ($property) does not exist");
            }
        }
        
        public function set_subject($subject) {
            $this->subject = $subject;
        }
        
        public function set_emailto($email, $name) {
            $this->emailto = array($email => $name); 
        }
        
        public function set_body($body, $html = true) {
            $stringer = new StringerBell();
            $this->hashtml = $html;
            $body .= "<br>". $this->user->name;
            $body .= "<br>" . $this->user->email;
            $body .= "<br>" . $stringer->format_needleforphone($this->user->phone) ;
            $this->body = $body;
        }
        
        public function set_file($filename) {
            $this->hasfile = true;
            $this->file = $filename;
        }
        
        public function set_cc($email, $name) {
            $this->cc = array($name => $email);
        }
        
        public function set_bcc($email, $name) {
            $this->bcc = array($name => $email);
        }
        
        public function set_filedirectory($dir) {
            $this->filedirectory = $dir;
        }
        
        /* =============================================================
           CLASS FUNCTIONS
        ============================================================ */
        public function send() {
            $emailer = SimpleMail::make()
            ->setSubject($this->subject)
            ->setMessage($this->body);
            
            foreach ($this->emailto as $email => $name) {
                $emailer->setTo($email, $name);
            }
            
            foreach ($this->emailfrom as $email => $name) {
                $emailer->setFrom($email, $name);
            }
            
            foreach ($this->replyto as $email => $name) {
                $emailer->setReplyTo($email, $name);
            }
            // setBcc allows setting from Array
            $emailer->setBcc($this->bcc);
            
            if ($this->hasfile) {
                if (strpos($this->filedirectory, $this->file) !== false) {
                    $emailer->addAttachment($this->filedirectory.$this->file);
                } else {
                    $emailer->addAttachment($this->file);
                }
            }
            
            if ($this->hashtml) {
                $emailer->setHtml();
            }
            
            return $emailer->send();
        }
        
        
    }
