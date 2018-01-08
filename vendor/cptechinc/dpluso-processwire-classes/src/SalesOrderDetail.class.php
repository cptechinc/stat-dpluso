<?php 
    class SalesOrderDetail extends OrderDetail implements OrderDetailInterface {
        public $sessionid;
        public $recno;
        public $date;
        public $time;
        public $type;
        public $orderno;
        public $linenbr;
        public $sublinenbr;
        public $itemid;
        public $custid;
        public $custitemid;
        public $desc1;
        public $desc2;
        public $price;
        public $extamt;
        public $qtyordered;
        public $qtyshipped;
        public $qtybackord;
        public $rshipdate;
        public $haveitemdoc;
        public $qtyavail;
        public $havenote;
        public $cost;
        public $whse;
        public $uom;
        public $spcord;
        public $kititemflag;
        public $promocode;
        public $taxcode;
        public $taxcodeperc;
        public $discpct;
        public $listprice;
        public $uomconv;
        public $vendorid;
        public $vendoritemid;
        public $mfgid;
        public $mfgitemid;
        public $status;
        public $lostreason;
        public $lostdate;
        public $notes;
        public $leaddays;
        public $ordrcosttot;
        public $costuom;
        public $stancost;
        public $quotind;
        public $quotunit;
        public $quotprice;
        public $quotcost;
        public $quotmkupmarg;
        public $quotdiscpct;
        public $errormsg;
        public $minprice;
        public $ponbr;
        public $poref;
        public $nsitemgroup;
        public $shipfromid;
        public $itemtype;
        public $dummy;
        
        public function has_error() {
            return !empty($this->errormsg);
        }
        
        public function is_kititem() {
            return $this->kitemflag == 'Y' ? true : false;
        }
        
        public function has_note() {
            return $this->havenote == 'Y' ? true : false;
        }
        
        public function has_documents() {
            return $this->haveitemdoc == 'Y' ? true : false;
        }
    }
