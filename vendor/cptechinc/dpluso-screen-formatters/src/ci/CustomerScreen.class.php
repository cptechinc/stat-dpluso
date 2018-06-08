<?php
	class CI_CustomerScreen extends TableScreenMaker {
		protected $tabletype = 'normal'; // grid or normal
		protected $type = 'ci-customer-page'; // ii-sales-history
		protected $title = 'Customer Screen';
		protected $datafilename = 'cicustomer'; // iisaleshist.json
		protected $testprefix = 'cicust'; // iish


		public function generate_screen() {
			$content = '';

			if (sizeof($this->json['data']) > 0) {

			} else {
				$content = $page->bootstrap->createalert('warning', 'Information Not Available');
			} // END if (sizeof($this->json['data']) > 0)
			return $content;
		}

		public function generate_customertable(Customer $customer) {
			$bootstrap = new Contento();
			$tb = new Table("class=table table-striped table-bordered table-condensed table-excel");
			foreach (array_keys($this->json['columns']['top']) as $column) {
				if ($this->json['columns']['top'][$column]['heading'] == '' && $this->json['data']['top'][$column] == '') {

				} else {
					$tb->tr();
					$tb->td('', $this->json['columns']['top'][$column]['heading']);
					if ($column == 'customerid') {
						$tb->td('', $this->generate_pageform($customer));
					} else {
						$tb->td('', $this->json['data']['top'][$column]);
					}
				}
			}
			if (has_dpluspermission(DplusWire::wire('user')->loginid, 'eso') || has_dpluspermission(DplusWire::wire('user')->loginid, 'eqo')) {
				$button = $bootstrap->button('type=button|class=btn btn-primary|data-toggle=modal|data-target=#item-lookup-modal', $bootstrap->createicon('glyphicon glyphicon-plus'). ' Item Entry');
				$tb->tr()->td('colspan=2', $bootstrap->p('class=text-center', $button));
			}
			return $tb->close();
		}

		public function generate_shiptotable(Customer $customer) {
			$bootstrap = new Contento();
			$tb = new Table("class=table table-striped table-bordered table-condensed table-excel");
			foreach (array_keys($this->json['columns']['top']) as $column) {
				if ($this->json['columns']['top'][$column]['heading'] == '' && $this->json['data']['top'][$column] == '') {

				} else {
					$tb->tr();

					if ($column == 'customerid') {
						$attr = (!$customer->has_shipto()) ? 'value= |selected' : 'value= ';
						$options = $bootstrap->option($attr, 'No Shipto Selected');
						$shiptos = get_customershiptos($customer->custID);
						foreach ($shiptos as $shipto) {
							$show = $shipto->shiptoid.' '.$shipto->name.' - '.$shipto->city.', '.$shipto->state;
							$options .= $bootstrap->option("value=$shipto->shiptoid", $show);
						}
						$select = $bootstrap->openandclose('select', "class=form-control input-sm|onchange=refreshshipto(this.value, '$customer->custID')", $options);
						$tb->td('', 'Ship-to ID');
						$tb->td('', $select);
					} else {
						$tb->td('', $this->json['columns']['top'][$column]['heading']);
						$tb->td('', $this->json['data']['top'][$column]);
					}
				}
			}
			return $tb->close();
		}

		public function generate_pageform(Customer $customer) {
			$action = Dpluswire::wire('config')->pages->ajax."load/customers/cust-index/";
			$form = new FormMaker("action=$action|method=POST|id=ci-cust-lookup|class=allow-enterkey-submit");
			$form->input("type=hidden|name=action|value=ci-item-lookup");
			$form->input("type=hidden|name=shipID|class=shipID|value=$customer->shipID");
			$form->input("type=hidden|name=nextshipID|class=nextshipID|value=".$customer->get_nextshiptoid());
			$input = $form->bootstrap->input("type=text|class=form-control input-sm not-round custID|name=custID|value=$customer->custID");
			$button = $form->bootstrap->button('type=submit|class=btn btn-sm btn-default not-round', $form->bootstrap->createicon('glyphicon glyphicon-search'));  //FIX for accessibility
			$span = $form->bootstrap->span('class=input-group-btn', $button);
			$form->add($form->bootstrap->div('class=input-group custom-search-form', $input.$span));
			return $form->finish();
		}

		public function generate_tableleft() {
			$tb = new Table('class=table table-striped table-bordered table-condensed table-excel');
			foreach (array_keys($this->json['columns']['left']) as $column) {
				if ($this->json['columns']['left'][$column]['heading'] == '' && $this->json['data']['left'][$column] == '') {

				} else {
					$tb->tr();
					$class = DplusWire::wire('config')->textjustify[$this->json['columns']['left'][$column]['headingjustify']];
					$tb->td("class=$class", $this->json['columns']['left'][$column]['heading']);
					$tb->td('', TableScreenMaker::generate_celldata($this->json['data']['left'], $column));
				}
			}
			return $tb->close();
		}

		public function generate_tableright() {
			$tb = new Table('class=table table-striped table-bordered table-condensed table-excel');
			foreach (array('activity', 'saleshistory') as $section) {
				if ($section != 'rfml') {
					$tb->tablesection('thead');
					$tb->tr();
					foreach ($this->json['columns']['right'][$section] as $column) {
						$class = DplusWire::wire('config')->textjustify[$column['headingjustify']];
						$tb->th("class=$class", $column['heading']);
					}
					$tb->closetablesection('thead');
					foreach (array_keys($this->json['data']['right'][$section]) as $row) {
						$tb->tr();
						foreach (array_keys($this->json['data']['right'][$section][$row]) as $column) {
							$class = DplusWire::wire('config')->textjustify[$this->json['columns']['right'][$section][$column]['datajustify']];
							$tb->td("class=$class", $this->json['data']['right'][$section][$row][$column]);
						}
					}
					$tb->tr('class=last-section-row')->td('colspan='.sizeof(array_keys($this->json['data']['right'][$section][$row])), '&nbsp;');
				}
			}

			foreach(array_keys($this->json['data']['right']['misc']) as $misc) {
				if ($misc != 'rfml') {
					$tb->tr();
					$class = DplusWire::wire('config')->textjustify[$this->json['columns']['right']['misc'][$misc]['headingjustify']];
					$tb->td("class=$class", $this->json['columns']['right']['misc'][$misc]['heading']);
					$class = DplusWire::wire('config')->textjustify[$this->json['columns']['right']['misc'][$misc]['datajustify']];
					$tb->td("class=$class", $this->json['data']['right']['misc'][$misc])->td();
				}
			}
			return $tb->close();
		}
	}
