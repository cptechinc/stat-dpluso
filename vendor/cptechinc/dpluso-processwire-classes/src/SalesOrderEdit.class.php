<?php 

    class SalesOrderEdit extends SalesOrder {
        
        public function generate_loaddocumentslink(SalesOrderPanel $orderpanel, $linenbr = '0') {
            $bootstrap = new Contento();
            $href = $this->generate_documentsrequesturl($orderpanel);
            $icon = $bootstrap->createicon('material-icons md-36', '&#xE873;');
            $ajaxdata = "data-loadinto=.docs|data-focus=.docs|data-click=#documents-link";
            
            if ($this->has_documents()) {
                return $bootstrap->openandclose('a', "href=$href|class=btn btn-primary load-sales-docs|role=button|title=Click to view Documents|$ajaxdata", $icon. ' Show Documents');
            } else {
                return $bootstrap->openandclose('a', "href=#|class=btn btn-default|title=No Documents Available", $icon. ' 0 Documents Found');
            }
        }
        
        public function generate_loadnoteslink(SalesOrderPanel $orderpanel, $linenbr) {
			$bootstrap = new Contento();
			$href = $this->generate_dplusnotesrequesturl($orderpanel, $linenbr);
			
			if ($this->can_edit()) {
				$title = ($this->has_notes()) ? "View and Create Order Notes" : "Create Order Notes";
			} else {
				$title = ($this->has_notes()) ? "View Order Notes" : "View Order Notes";
			}
			$content = $bootstrap->createicon('material-icons md-36', '&#xE0B9;');
			$link = $bootstrap->openandclose('a', "href=$href|class=btn btn-default load-notes|title=$title|data-modal=$orderpanel->modal", $content);
			return $link;
		}
        
        public function generate_loadtrackinglink(SalesOrderPanel $orderpanel) {
            $bootstrap = new Contento();
            $href = $this->generate_documentsrequesturl($orderpanel);
            $icon = $bootstrap->createicon('glyphicon glyphicon-plane hover');
            $ajaxdata = "data-loadinto=.tracking|data-focus=.tracking|data-click=#tracking-tab-link";
            
            if ($this->has_tracking()) {
                return $bootstrap->openandclose('a', "href=$href|class=btn btn-primary load-sales-tracking|role=button|title=Click to load tracking|$ajaxdata", $icon. ' Show Documents');
            } else {
                return $bootstrap->openandclose('a', "href=#|class=btn btn-default|title=No Tracking Available", $icon. ' No Tracking Available');
            }
        }
        
    }
    
    
