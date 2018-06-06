<?php
	class II_ItemPageFormatter extends TableScreenFormatter {
        protected $tabletype = 'grid'; // grid or normal
		protected $type = 'ii-item-page'; // ii-sales-history
		protected $title = 'Item Page';
		protected $datafilename = 'iiitem'; // iisaleshist.json
		protected $testprefix = 'iiitemid'; // iish
		protected $formatterfieldsfile = 'iihfmattbl'; // iishfmtbl.json
		protected $datasections = array(
			"header" => "Header"
		);

        public function generate_screen() {
            $bootstrap = new Contento();
            $content = '';
			$this->generate_tableblueprint();
			$item = getitemfromim($this->json['itemid'], false);
			$specs = $pricing = $item;
			$imagediv = $bootstrap->div('class=col-sm-4 form-group', $bootstrap->img("src=".Processwire\wire('config')->imagedirectory.$item['image']."|class=img-responsive|data-desc=".$item['itemid'].' image'));
			$itemform = $bootstrap->div('class=col-sm-8', $this->generate_itemformsection());
			$content .= $bootstrap->div('class=row', $imagediv . $itemform);
			$content .= $bootstrap->div('class=row', $this->generate_othersections());
            return $content;
        }

		protected function generate_itemimagesource() {
			$item = getitemfromim($this->json['itemid'], false);
			$file = file_exists(Processwire\wire('config')->imagefiledirectory.$item['image']) ? $item['image'] : Processwire\wire('config')->imagenotfound;
			return Processwire\wire('config')->imagedirectory.$file;
		}

		protected function generate_itemformsection() {
			$bootstrap = new Contento();
			$itemID = $this->json['itemid'];
			$custID = Processwire\wire('input')->get->text('custID');
			$shipID = Processwire\wire('input')->get->text('shipID');
			$tb = new Table('class=table table-striped table-bordered table-condensed table-excel');

			foreach ($this->tableblueprint['header']['sections']['1'] as $column) {
				$tb->tr();
				$class = Processwire\wire('config')->textjustify[$this->fields['data']['header'][$column['id']]['headingjustify']];
				$colspan = $column['col-length'];
				$tb->td("colspan=$colspan|class=$class", $bootstrap->b('', $column['label']));
				$class = Processwire\wire('config')->textjustify[$this->fields['data']['header'][$column['id']]['datajustify']];
				$colspan = $column['col-length'];
				if ($column['id'] == 'Item ID') {
					$action = Processwire\wire('config')->pages->ajax."load/ii/search-results/modal/";
					$form = new FormMaker("action=$action|method=POST|id=ii-item-lookup|class=allow-enterkey-submit");
					$form->input('type=hidden|name=action|value=ii-item-lookup');
					$form->input("type=hidden|name=custID|class=custID|value=$custID");
					$form->input("type=hidden|name=shipID|class=shipID|value=$shipID");
					$form->div('class=form-group', false);
						$form->div('class=input-group custom-search-form', false);
							$form->input("type=text|class=form-control not-round itemID|name=itemID|placeholder=Search ItemID, X-ref|value=$itemID");
							$button = $form->bootstrap->button('type=submit|class=btn btn-default not-round', $form->bootstrap->createicon('glyphicon glyphicon-search'));
							$form->span('class=input-group-btn', $button);
						$form->close('div');
					$form->close('div');
					$form->input('type=hidden|class=prev-itemID|value='.getitembyrecno(getnextrecno($itemID, "prev", false), false));
					$form->input('type=hidden|class=next-itemID|value='.getitembyrecno(getnextrecno($itemID, "next", false), false));
					$celldata = $form->finish();
				} else {
					$celldata = Table::generatejsoncelldata($this->fields['data']['header'][$column['id']]['type'], $this->json['data'], $column);
				}
				$tb->td("colspan=$colspan|class=$class", $celldata);
			}
			return $tb->close();
		}

		protected function generate_othersections() {
			$bootstrap = new Contento;
			$content = '';
			for ($i = 2; $i < 5; $i++) {
				$content .= $bootstrap->open('div', 'class=col-sm-4 form-group');
				$tb = new Table('class=table table-striped table-bordered table-condensed table-excel');

				foreach ($this->tableblueprint['header']['sections']["$i"] as $column) {
					$tb->tr();
					$class = Processwire\wire('config')->textjustify[$this->fields['data']['header'][$column['id']]['headingjustify']];
					$colspan = $column['col-length'];
					$tb->td("colspan=$colspan|class=$class", $bootstrap->b('', $column['label']));

					$class = Processwire\wire('config')->textjustify[$this->fields['data']['header'][$column['id']]['datajustify']];
					$colspan = $column['col-length'];
					$celldata = Table::generatejsoncelldata($this->fields['data']['header'][$column['id']]['type'], $this->json['data'], $column);
					$tb->td("colspan=$colspan|class=$class", $celldata);
				}
				$content .=  $tb->close();
				$content .=  $bootstrap->close('div');
			}
			return $content;
		}

		protected function generate_tableblueprint() {
			$table = array(
				'header' => array(
					'sections' => array(
						'1' => array(),
						'2' => array(),
						'3' => array(),
						'4' => array()
					)
				)
			);

			for ($i = 1; $i < 5; $i++) {
				foreach(array_keys($this->formatter['header']['columns']) as $column) {
					if ($this->formatter['header']['columns'][$column]['column'] == $i) {
						$col = array(
							'id' => $column,
							'label' => $this->formatter['header']['columns'][$column]['label'],
							'column' => $this->formatter['header']['columns'][$column]['column'],
							'col-length' => $this->formatter['header']['columns'][$column]['col-length'],
							'before-decimal' => $this->formatter['header']['columns'][$column]['before-decimal'],
							'after-decimal' => $this->formatter['header']['columns'][$column]['after-decimal'],
							'date-format' => $this->formatter['header']['columns'][$column]['date-format']
						 );
						$table['header']['sections'][$i][$this->formatter['header']['columns'][$column]['line']] = $col;
					}
				}
			}
			$this->tableblueprint = $table;
        }
    }
