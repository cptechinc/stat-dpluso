<?php
    class EditUserActionsDisplay extends UserActionDisplay {
        /* =============================================================
 		   CLASS FUNCTIONS 
 	   ============================================================ */
        public function generate_selectsubtype(UserAction $action) {
            $bootstrap = new Contento();
            $subtypes = Processwire\wire('pages')->get("/activity/$action->actiontype/")->children();
            $content = '';
            
            foreach ($subtypes as $subtype) {
                if ($subtype->name == $action->actionsubtype) {
                    $content .= $bootstrap->openandclose('button', "class=btn btn-primary select-button-choice btn-sm|type=button|data-value=$subtype->name", $subtype->subtypeicon . ' '. $subtype->actionsubtypelabel);
                } else {
                    $content .= $bootstrap->openandclose('button', "class=btn btn-default select-button-choice btn-sm|type=button|data-value=$subtype->name", $subtype->subtypeicon . ' '. $subtype->actionsubtypelabel);
                }
            }
            
            $content .= $bootstrap->open('input', "type=hidden|class=select-button-value required|name=subtype|value=$action->actionsubtype");
            return $content;
        }
        
        public function generate_selectsalesperson($salespersonID) {
            $bootstrap = new Contento();
            $salespersonarray = json_decode(file_get_contents(Processwire\wire('config')->companyfiles."json/salespersontbl.json"), true);
			$salesids = array_keys($salespersonarray['data']);
			$salespeople = array();
            
			foreach ($salesids as $salesID) {
				$salespeople[$salespersonarray['data'][$salesID]['splogin']] = $salespersonarray['data'][$salesID]['spname'];
			}
            return $bootstrap->select("name=assignedto|class=form-control input-sm|style=width: 200px;", $salespeople, $salespersonID);
        }
    }
