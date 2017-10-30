<?php 

    class QuoteDetail extends OrderDetail implements OrderDetailInterface {
        public $sessionid;
        public $recno;
        public $date;
        public $time;
        public $quotenbr;
        public $custid;
        public $linenbr;
        public $sublinenbr;
        public $itemid;
        public $desc1;
        public $desc2;
        public $custitemid;
        public $vendorid;
        public $vendoritemid;
        public $status;
        public $lostreason;
        public $lostdate;
        public $kititemflag;
        public $notes;
        public $venddetail;
        public $rshipdate;
        public $leaddays;
        public $taxcode;
        public $ordrqty;
        public $ordrprice;
        public $ordrcost;
        public $ordrpricetot;
        public $ordrcosttot;
        public $uom;
        public $costuom;
        public $whse;
        public $listprice;
        public $stancost;
        public $quotind;
        public $quotunit;
        public $quotprice;
        public $quotcost;
        public $quotmkupmarg;
        public $discpct;
        public $spcord;
        public $error;
        public $errormsg;
        public $minprice;
        public $nsitemgroup;
        public $shipfromid;
        public $itemtype;
        public $dummy;
        
        public function has_error() {
            return $this->error == 'Y' ? true : false;
        }
        
        public function is_kititem() {
            return $this->kitemflag == 'Y' ? true : false;
        }
        
        public function has_note() {
            return $this->notes == 'Y' ? true : false;
        }
        
        public function has_documents() {
            //return $this->notes == 'Y' ? true : false;
            return false;
        }
    }
