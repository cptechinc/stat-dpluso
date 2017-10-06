<?php 

    class Quote {
        public $sessionid;
        public $recno;
        public $date;
        public $time;
        public $type;
        public $custid;
        public $shiptoid;
        public $custname;
        public $orderno;
        public $custpo;
        public $custref;
        public $status;
        public $orderdate;
        public $careof;
        public $quotdate;
        public $invdate;
        public $shipdate;
        public $revdate;
        public $expdate;
        public $havedoc;
        public $havetrk;
        public $odrsubtot;
        public $odrtax;
        public $odrfrt;
        public $odrmis;
        public $odrtotal;
        public $havenote;
        public $editord;
        public $error;
        public $errormsg;
        public $sconame;
        public $sname;
        public $saddress;
        public $saddress2;
        public $scity;
        public $sst;
        public $szip;
        public $scountry;
        public $contact;
        public $phintl;
        public $phone;
        public $extention;
        public $faxnumber;
        public $email;
        public $releasenbr;
        public $shipviacd;
        public $shipviadesc;
        public $priccode;
        public $pricdesc;
        public $pricdisp;
        public $taxcode;
        public $taxcodedesc;
        public $taxcodedisp;
        public $termcode;
        public $termtype;
        public $termdesc;
        public $rqstdate;
        public $shipcom;
        public $sp1;
        public $sptname;
        public $sp2;
        public $sp2name;
        public $sp2disp;
        public $sp3;
        public $sp3name;
        public $sp3disp;
        public $fob;
        public $deliverydesc;
        public $whse;
        public $ccno;
        public $xpdate;
        public $ccvalidcode;
        public $ccapproval;
        public $costtot;
        public $totdisc;
        public $paytype;
        public $srcdatefrom;
        public $srcdatethru;
        public $btname;
        public $btadr1;
        public $btadr2;
        public $btadr3;
        public $btctry;
        public $btcity;
        public $btstate;
        public $btzip;
        public $prntfmt;
        public $prntfmtdisp;
        public $dummy;
        
        public $canedit = true;
        public $hasnotes = false;
        
        
        public function __construct() {
            if ($this->havenote == 'Y') { $this->hasnotes = true; }
        }

        
        public static function returnclassarray() {
            return SalesOrder::unsetnondbkeys(get_class_vars('Quote'));
        }
        
        public static function unsetnondbkeys($array) {
            $array = get_class_vars('Quote');
            unset($array['canedit']);
            unset($array['hasnotes']);
            return $array;
        }
        
    }
    
    
