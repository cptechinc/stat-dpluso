<?php 
     class II_ItemActivityScreen extends TableScreenMaker {
		protected $tabletype = 'normal'; // grid or normal
		protected $type = 'ii-activity'; 
		protected $title = 'Item Activity';
		protected $datafilename = 'iiactivity'; 
		protected $testprefix = 'iiact';
		protected $datasections = array();
        
        /* =============================================================
          PUBLIC FUNCTIONS
       	============================================================ */
        public function generate_screen() {
            $bootstrap = new Contento();
            $content = '';
            
            foreach($this->json['data'] as $warehouse) {
				$content .= $bootstrap->h3('', $warehouse['Whse Name']);
                
				$tb = new Table('class=table table-striped table-bordered table-condensed table-excel|id=activity');
				$tb->tablesection('thead');
					$tb->tr();
					foreach($this->json['columns'] as $column)  {
						$class = wire('config')->textjustify[$column['headingjustify']];
						$tb->th("class=$class", $column['heading']);
					}
				$tb->closetablesection('thead');
				$tb->tablesection('tbody');
					foreach($warehouse['orders'] as $order) {
						$tb->tr();
						foreach(array_keys($this->json['columns']) as $column) {
							$class = wire('config')->textjustify[$this->json['columns'][$column]['datajustify']];
							$tb->td("class=$class", $order[$column]);
						}
					}
				$tb->closetablesection('tbody');
				$content .= $tb->close();
			}
            return $content;
        }
        
        public function generate_javascript() {
			$bootstrap = new Contento();
			$content = $bootstrap->open('script', '');
				$content .= "\n";
				$content .= $bootstrap->indent().'$(function() {';
					$content .= $bootstrap->indent() . $bootstrap->indent() ."$('#activity').DataTable();";
				$content .= $bootstrap->indent().'});';
				$content .= "\n";
			$content .= $bootstrap->close('script');
			return $content;
		}
        
        public function generate_activityform($itemID, array $iiconfig) {
            if (trim($iiconfig['activity']['date-options']['start-date']) != '') {
                $date = date("m/d/y", strtotime($iiconfig['activity']['date-options']['start-date']));
            } else {
                $date = date("m/d/y", strtotime("-".$iiconfig['activity']['date-options']['days-back']." day"));
            }
            $action = wire('config')->pages->products."redir/";
            $form = new FormMaker("action=$action|method=post|id=ii-item-activity-form");
            $form->input("type=hidden|name=action|value=ii-activity");
            $form->input("type=hidden|name=itemID|value=$itemID");
            $content = $form->bootstrap->div('class=form-group', $form->bootstrap->p('', "Item $itemID"));
            $content .= $form->bootstrap->div('class=form-group', $form->boostrap->label('', 'Starting Report Date') . $form->bootstrap->div('class=input-group date', $form->datepicker('date', $date)));
            $form->add($form->bootstrap->div('class=row', $form->bootstrap->div('col-xs-10', $content)));
            return $form->finish();
        }
    }
