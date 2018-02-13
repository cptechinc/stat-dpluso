<?php 
    class PDFMaker extends mikehaertl\wkhtmlto\Pdf {
        use ThrowErrorTrait;
        
        protected $url = false;
        protected $sessionID = false;
        protected $options = array(
            'binary' => '/usr/local/bin/wkhtmltopdf',
            // Explicitly tell wkhtmltopdf that we're using an X environment
            'use-xserver',
            // Enable built in Xvfb support in the command
            'commandOptions' => array(
                'enableXvfb' => true,
                // Optional: Set your path to xvfb-run. Default is just 'xvfb-run'.
                // 'xvfbRunBinary' => '/usr/bin/xvfb-run',
                // Optional: Set options for xfvb-run. The following defaults are used.
                // 'xvfbRunOptions' =>  '--server-args="-screen 0, 1024x768x24"',
            ),
        );
        
        public function __construct($sessionID, $url) {
            parent::__construct($this->options);
            $this->sessionID = $sessionID;
            $this->url = $url;
        }
        
        public function process() {
            $file = wire('config')->documentstoragedirectory.$this->sessionID.".pdf";
            
            if (file_exists($file)) {
                unlink($file);
            }
            $this->addPage($this->url);
            
            if (!$this->saveAs($file)) {
                $this->error($this->getError());
                return false;
            }
            //echo $this->getCommand()->getOutput();
            return $file;
        }
        
        
    }
