<?php
	use atk4\dsql\Query;
/* =============================================================
	LOGIN FUNCTIONS
============================================================ */
	function is_validlogin($sessionID) {
		$sql = Processwire\wire('database')->prepare("SELECT IF(validlogin = 'Y',1,0) FROM logperm WHERE sessionid = :sessionID LIMIT 1");
		$switching = array(':sessionID' => $sessionID);
		$sql->execute($switching);
		return $sql->fetchColumn();
	}

	function get_loginerrormsg($sessionID) {
		$sql = Processwire\wire('database')->prepare("SELECT errormsg FROM logperm WHERE sessionid = :sessionID");
		$switching = array(':sessionID' => $sessionID);
		$sql->execute($switching);
		return $sql->fetchColumn();
	}

	function get_loginrecord($sessionID) {
		$sql = Processwire\wire('database')->prepare("SELECT IF(restrictcustomers = 'Y',1,0) as restrictcustomer, IF(restrictaccess = 'Y',1,0) as restrictuseraccess, logperm.* FROM logperm WHERE sessionid = :sessionID");
		$switching = array(':sessionID' => $sessionID);
		$sql->execute($switching);
		return $sql->fetch(PDO::FETCH_ASSOC);
	}
	
	function has_restrictedcustomers($sessionID, $debug = false) {
		$q = (new QueryBuilder())->table('logperm');
		$q->field($q->expr("IF(restrictcustomers = 'Y',1,0)"));
		$q->where('sessionid', $sessionID);
		$sql = Processwire\wire('database')->prepare($q->render());
		
		if ($debug) {
			return $q->generate_sqlquery($q->params);
		} else {
			$sql->execute($q->params);
			return $sql->fetchColumn();
		}
	}
/* =============================================================
	PERMISSION FUNCTIONS
============================================================ */
	function has_dpluspermission($loginID, $dplusfunction, $debug = false) {
		$q = (new QueryBuilder())->table('funcperm');
		$q->field($q->expr("IF(permission = 'Y',1,0)"));
		$q->where('loginid', $loginID);
		$q->where('function', $dplusfunction);
		$sql = Processwire\wire('database')->prepare($q->render());
		
		if ($debug) {
			return $q->generate_sqlquery($q->params);
		} else {
			$sql->execute($q->params);
			return $sql->fetchColumn();
		}
	}
/* =============================================================
	CUSTOMER FUNCTIONS
============================================================ */
	function is_custindexloaded($debug = false) {
		$q = (new QueryBuilder())->table('custindex');
		$q->field('COUNT(*)');
		$sql = Processwire\wire('database')->prepare($q->render());
		
		if ($debug) {
			return $q->generate_sqlquery($q->params);
		} else {
			$sql->execute($q->params);
			return $sql->fetchAll(PDO::FETCH_ASSOC);
		}
	}
	
	function count_custperm($userID = false, $debug = false) {
		$q = (new QueryBuilder())->table('custperm');
		$q->field('COUNT(*)');
		if ($userID) {
			$q->where('loginid', $userID);
		}
		$sql = Processwire\wire('database')->prepare($q->render());
		
		if ($debug) {
			return $q->generate_sqlquery($q->params);
		} else {
			$sql->execute($q->params);
			return $sql->fetchAll(PDO::FETCH_ASSOC);
		}
	}
	
	function can_accesscustomer($loginID, $restrictions, $custID, $debug) {
		$SHARED_ACCOUNTS = Processwire\wire('config')->sharedaccounts;
		if ($restrictions) {
			$sql = Processwire\wire('database')->prepare("SELECT COUNT(*) FROM (SELECT * FROM custperm WHERE custid = :custID) t WHERE loginid = :loginID OR loginid = :shared");
			$switching = array(':custID' => $custID, ':loginID' => $loginID, ':shared' => $SHARED_ACCOUNTS);
			$withquotes = array(true, true, true);
			if ($debug) {
				return returnsqlquery($sql->queryString, $switching, $withquotes);
			} else {
				$sql->execute($switching);
				return $sql->fetchColumn();
			}
		} else {
			return 1;
		}
	}

	function can_accesscustomershipto($loginID, $restrictions, $custID, $shipID, $debug) {
		$SHARED_ACCOUNTS = Processwire\wire('config')->sharedaccounts;
		if ($restrictions) {
			$sql = Processwire\wire('database')->prepare("SELECT COUNT(*) FROM (SELECT * FROM custperm WHERE custid = :custID AND shiptoid = :shipID) t WHERE loginid = :loginID OR loginid = :shared");
			$switching = array(':custID' => $custID, ':shipID' => $shipID, ':loginID' => $loginID, ':shared' => $SHARED_ACCOUNTS);
			$withquotes = array(true, true, true, true, true);
			if ($debug) {
				return returnsqlquery($sql->queryString, $switching, $withquotes);
			} else {
				$sql->execute($switching);
				return $sql->fetchColumn();
			}
		} else {
			return 1;
		}
	}
	
	function get_customer($custID, $shiptoID = false, $debug = false) {
		$q = (new QueryBuilder())->table('custindex');	
		$q->where('custid', $custID);
		
		if ($shiptoID) {
			$q->where('shiptoid', $shiptoID);
			$q->where('source', Contact::$types['customer-shipto']);
		} else {
			$q->where('source', Contact::$types['customer']);
		}
		
		$sql = Dpluswire::wire('database')->prepare($q->render());
		
		if ($debug) {
			return $q->generate_sqlquery($q->params);
		} else {
			$sql->execute($q->params);
			$sql->setFetchMode(PDO::FETCH_CLASS, 'Customer');
			return $sql->fetch();
		}
	}
	
	function get_customername($custID) {
		$sql = Dpluswire::wire('database')->prepare("SELECT name FROM custindex WHERE custid = :custID LIMIT 1");
		$switching = array(':custID' => $custID);
		$sql->execute($switching);
		return $sql->fetchColumn();
	}

	function get_shiptoname($custID, $shipID, $debug = false) {
		$sql = Dpluswire::wire('database')->prepare("SELECT name FROM custindex WHERE custid = :custID AND shiptoid = :shipID LIMIT 1");
		$switching = array(':custID' => $custID, ':shipID' => $shipID); $withquotes = array(true, true);
		if ($debug) {
			return returnsqlquery($sql->queryString, $switching, $withquotes);
		} else {
			$sql->execute($switching);
			return $sql->fetchColumn();
		}
	}

	function get_customerinfo($sessionID, $custID, $debug) { // DEPRECATE 
		$sql = Dpluswire::wire('database')->prepare("SELECT custindex.*, customer.dateentered FROM custindex JOIN customer ON custindex.custid = customer.custid WHERE custindex.custid = :custID AND customer.sessionid = :sessionID LIMIT 1");
		$switching = array(':sessionID' => $sessionID, ':custID' => $custID); $withquotes = array(true, true);
		if ($debug) {
			return returnsqlquery($sql->queryString, $switching, $withquotes);
		} else {
			$sql->execute($switching);
			return $sql->fetch(PDO::FETCH_ASSOC);
		}
	}

	function get_firstcustindexrecord($debug) {
		$sql = DplusWire::wire('database')->prepare("SELECT * FROM custindex LIMIT 1");
		if ($debug) {
			return $sql->queryString;
		} else {
			$sql->execute();
			return $sql->fetch(PDO::FETCH_ASSOC);
		}
	}

	function count_shiptos($custID, $loginID, $debug = false) { // TODO use QueryBuilder
		$SHARED_ACCOUNTS = DplusWire::wire('config')->sharedaccounts;
		
		if (DplusWire::wire('user')->hascontactrestrictions) {
			$custquery = (new QueryBuilder())->table('custperm')->where('custid', $custID)->where('shiptoid', '!=', '');
			$q = (new QueryBuilder())->table($custquery, 'custpermcust');
			$q->where('loginid', [$loginID, $SHARED_ACCOUNTS]);
		} else {
			$q = (new QueryBuilder())->table('custperm');
			$q->where('custid', $custID);
		}
		$q->field('COUNT(*)');
		$sql = DplusWire::wire('database')->prepare($q->render());
		if ($debug) {
			return $q->generate_sqlquery($q->params);
		} else {
			$sql->execute($q->params);
			return $sql->fetchColumn();
		}
	}

	function get_shiptoinfo($custID, $shipID, $debug) {
		$sql = DplusWire::wire('database')->prepare("SELECT * FROM custindex WHERE custid = :custID AND shiptoid = :shipID LIMIT 1");
		$switching = array(':custID' => $custID, ':shipID' => $shipID); $withquotes = array(true, true);
		if ($debug) {
			return returnsqlquery($sql->queryString, $switching, $withquotes);
		} else {
			$sql->execute($switching);
			return $sql->fetch(PDO::FETCH_ASSOC);
		}
	}

	function get_customershiptos($custID, $loginID, $debug = false) { // TODO use QueryBuilder
		$SHARED_ACCOUNTS = DplusWire::wire('config')->sharedaccounts;
		$q = (new QueryBuilder())->table('custindex');
		
		if (DplusWire::wire('user')->hascontactrestrictions) {
			$custquery = (new QueryBuilder())->table('custperm')->where('custid', $custID)->where('shiptoid', '!=', '');
			$permquery = (new QueryBuilder())->table($custquery, 'custpermcust');
			$permquery->field('custid, shiptoid');
			$permquery->where('loginid', [$loginID, $SHARED_ACCOUNTS]);
			$q->where('(custid, shiptoid)','in', $permquery);
		} else {
			$q->where('custid', $custID);
		}
		$q->group('custid, shiptoid');
		$sql = DplusWire::wire('database')->prepare($q->render());
		
		if ($debug) {
			return $q->generate_sqlquery($q->params);
		} else {
			$sql->execute($q->params);
			$sql->setFetchMode(PDO::FETCH_CLASS, 'Customer');
			return $sql->fetchAll();
		}
	}
	
	function get_topxsellingshiptos($sessionID, $custID, $count, $debug = false) {
		$loginID = (Dpluswire::wire('user')->hascontactrestrictions) ? Processwire\wire('user')->loginid : 'admin';
		$q = (new QueryBuilder())->table('custperm');
		$q->where('loginid', $loginID);
		$q->where('custid', $custID);
		$q->where('shiptoid', '!=', '');
		$q->limit($count);
		$q->order('amountsold DESC');
		$sql = Dpluswire::wire('database')->prepare($q->render());
		
		if ($debug) {
			return $q->generate_sqlquery($q->params);
		} else {
			$sql->execute($q->params);
			return $sql->fetchAll(PDO::FETCH_ASSOC);
		}
	}
	
	function count_customercontacts($loginID, $restrictions, $custID, $debug = false) {
		$SHARED_ACCOUNTS = Dpluswire::wire('config')->sharedaccounts;
		$q = (new QueryBuilder())->table('custindex');
		$q->field('COUNT(*)');
		
		if ($restrictions) {
			$custquery = (new QueryBuilder())->table('custperm')->where('custid', $custID);
			$permquery = (new QueryBuilder())->table($custquery, 'custpermcust');
			$permquery->field('custid, shiptoid');
			$permquery->where('loginid', [$loginID, $SHARED_ACCOUNTS]);
			$q->where('(custid, shiptoid)','in', $permquery);
		} else {
			$q->where('custid', $custID);
		}
		
		$sql = Processwire\wire('database')->prepare($q->render());
		
		if ($debug) {
			return $q->generate_sqlquery($q->params);
		} else {
			$sql->execute($q->params);
			return $sql->fetchColumn();
		}
	}

	function get_customercontacts($loginID, $restrictions, $custID, $debug = false) {
		$SHARED_ACCOUNTS = Dpluswire::wire('config')->sharedaccounts;
		$q = (new QueryBuilder())->table('custindex');
		
		if ($restrictions) {
			$custquery = (new QueryBuilder())->table('custperm')->where('custid', $custID);
			$permquery = (new QueryBuilder())->table($custquery, 'custpermcust');
			$permquery->field('custid, shiptoid');
			$permquery->where('loginid', [$loginID, $SHARED_ACCOUNTS]);
			$q->where('(custid, shiptoid)','in', $permquery);
		} else {
			$q->where('custid', $custID);
		}
		
		$sql = Dpluswire::wire('database')->prepare($q->render());
		
		if ($debug) {
			return $q->generate_sqlquery($q->params);
		} else {
			$sql->execute($q->params);
			$sql->setFetchMode(PDO::FETCH_CLASS, 'Contact');
			return $sql->fetchAll();
		}
	}

	function can_accesscustomercontact($loginID, $restrictions, $custID, $shipID, $contactID, $debug) {
		$SHARED_ACCOUNTS = Processwire\wire('config')->sharedaccounts;
		if ($restrictions) {
			$sql = Processwire\wire('database')->prepare("SELECT COUNT(*) FROM custindex WHERE (custid, shiptoid) IN (SELECT custid, shiptoid FROM (SELECT * FROM custperm WHERE custid = :custID) t WHERE loginid = :loginID OR loginid = :shared) AND shiptoid = :shipID AND contact = :contactID");
			$switching = array(':custID' => $custID, ':loginID' => $loginID, ':shared' => $SHARED_ACCOUNTS, ':shipID' => $shipID, ':contactID' => $contactID);
			$withquotes = array(true, true, true, true, true);
		} else {
			$sql = Processwire\wire('database')->prepare("SELECT COUNT(*) FROM custindex WHERE custid = :custID AND shiptoid = :shipID AND contact = :contactID");
			$switching = array(':custID' => $custID, ':shipID' => $shipID, ':contactID' => $contactID);
			$withquotes = array(true, true, true);
		}
		$sql->execute($switching);
		if ($debug) { return returnsqlquery($sql->queryString, $switching, $withquotes); } else { if ($sql->fetchColumn() > 0){return true;} else {return false; } }
	}

	function get_customercontact($custID, $shiptoID = '', $contactID = '', $debug = false) {
		$q = (new QueryBuilder())->table('custindex');
		$q->limit(1);
		$q->where('custid', $custID);
		$q->where('shiptoid', $shiptoID);
		if (!empty($contactID)) {
			$q->where('contact', $contactID);
		}
		$sql = Processwire\wire('database')->prepare($q->render());
		
		if ($debug) {
			return $q->generate_sqlquery($q->params);
		} else {
			$sql->execute($q->params);
			$sql->setFetchMode(PDO::FETCH_CLASS, 'Contact');
			return $sql->fetch();
		}
	}
	
	/**
	 * Gets the primary contact for that Customer Shipto.
	 * ** NOTE each Customer and Customer Shipto may have one Primary buyer
	 * @param  string  $custID   Customer ID
	 * @param  bool $shiptoID Shipto ID ** optional
	 * @param  bool $debug    Determines if query will execute and if SQL is returned or Contact object
	 * @return Contact            Or SQL QUERY
	 */
	function get_primarybuyercontact($custID, $shiptoID = false, $debug = false) {
		$q = (new QueryBuilder())->table('custindex');
		$q->limit(1);
		$q->where('custid', $custID);
		if (!empty($shiptoID)) {
			$q->where('shiptoid', $shiptoID);
		}
		$q->where('buyingcontact', 'P');
		$sql = Processwire\wire('database')->prepare($q->render());
		
		if ($debug) {
			return $q->generate_sqlquery($q->params);
		} else {
			$sql->execute($q->params);
			$sql->setFetchMode(PDO::FETCH_CLASS, 'Contact');
			return $sql->fetch();
		}
	}
	
	function get_customerbuyersendusers($loginID, $custID, $shiptoID = false, $debug = false) {
		$SHARED_ACCOUNTS = DplusWire::wire('config')->sharedaccounts;
		$q = (new QueryBuilder())->table('custindex');
		
		if (DplusWire::wire('user')->hascontactrestrictions) {
			$custquery = (new QueryBuilder())->table('custperm')->where('custid', $custID);
			if (!empty($shiptoID)) {
				$custquery->where('shiptoid', $shiptoID);
			}
			$permquery = (new QueryBuilder())->table($custquery, 'custpermcust');
			$permquery->field('custid, shiptoid');
			$permquery->where('loginid', [$loginID, $SHARED_ACCOUNTS]);
			$q->where('(custid, shiptoid)','in', $permquery);
		} else {
			$q->where('custid', $custID);
			if (!empty($shiptoID)) {
				$q->where('shiptoid', $shiptoID);
			}
		}
		$q->where('buyingcontact', '!=', 'N');
		$q->where('certcontact', 'Y');
		
		$sql = DplusWire::wire('database')->prepare($q->render());
		
		if ($debug) {
			return $q->generate_sqlquery($q->params);
		} else {
			$sql->execute($q->params);
			$sql->setFetchMode(PDO::FETCH_CLASS, 'Contact');
			return $sql->fetchAll();
		}
	}
	
	function search_customerbuyersendusers($loginID, $query, $custID, $shiptoID = false, $debug = false) {
		$SHARED_ACCOUNTS = DplusWire::wire('config')->sharedaccounts;
		$search = '%'.$query.'%';
		$q = (new QueryBuilder())->table('custindex');
		
		if (DplusWire::wire('user')->hascontactrestrictions) {
			$custquery = (new QueryBuilder())->table('custperm')->where('custid', $custID);
			if (!empty($shiptoID)) {
				$custquery->where('shiptoid', $shiptoID);
			}
			$permquery = (new QueryBuilder())->table($custquery, 'custpermcust');
			$permquery->field('custid, shiptoid');
			$permquery->where('loginid', [$loginID, $SHARED_ACCOUNTS]);
			$q->where('(custid, shiptoid)','in', $permquery);
		} else {
			$q->where('custid', $custID);
			if (!empty($shiptoID)) {
				$q->where('shiptoid', $shiptoID);
			}
		}
		$fieldstring = implode(", ' ', ", array_keys(Contact::generate_classarray()));
		$q->where('buyingcontact', '!=', 'N');
		$q->where('certcontact', 'Y');
		$q->where($q->expr("UCASE(REPLACE(CONCAT($fieldstring), '-', '')) LIKE []", [$search]));
		$sql = DplusWire::wire('database')->prepare($q->render());
		
		if ($debug) {
			return $q->generate_sqlquery($q->params);
		} else {
			$sql->execute($q->params);
			$sql->setFetchMode(PDO::FETCH_CLASS, 'Contact');
			return $sql->fetchAll();
		}
	}
	
	function edit_customercontact($custID, $shipID, $contactID, $contact, $debug = false) {
		$originalcontact = get_customercontact($custID, $shipID, $contactID, false);
		$q = (new QueryBuilder())->table('custindex');
		$q->mode('update');
		$q->generate_setdifferencesquery($originalcontact->_toArray(), $contact->_toArray());
		$q->where('custid', $custID);
		$q->where('shiptoid', $shipID);
		$q->where('contact', $contactID);
		$sql = Processwire\wire('database')->prepare($q->render());
		
		if ($debug) {
			return $q->generate_sqlquery();
		} else {
			$sql->execute($q->params);
			$success = $sql->rowCount();
			if ($success) {
				return array("error" => false, "sql" => $q->generate_sqlquery($q->params));
			} else {
				return array("error" => true, "sql" => $q->generate_sqlquery($q->params));
			}
		}
	}

	function get_customersalesperson($custID, $shipID, $debug) {
		$sql = Processwire\wire('database')->prepare("SELECT splogin1 FROM custindex WHERE custid = :custID AND shiptoid = :shipID LIMIT 1");
		$switching = array(':custID' => $custID, ':shipID' => $shipID);
		$withquotes = array(true, true);

		if ($debug) {
			return returnsqlquery($sql->queryString, $switching, $withquotes);
		} else {
			$sql->execute($switching);
			return $sql->fetchColumn();
		}
	}
/* =============================================================
	CUST INDEX FUNCTIONS
============================================================ */
	function get_distinctcustindexpaged($loginID, $limit = 10, $page = 1, $restrictions, $debug) {
		$SHARED_ACCOUNTS = Processwire\wire('config')->sharedaccounts;
		$limiting = returnlimitstatement($limit, $page);

		if ($restrictions) {
			$sql = Processwire\wire('database')->prepare("SELECT * FROM custindex WHERE custid IN (SELECT DISTINCT(custid) FROM custperm WHERE loginid = :loginID OR loginid = :shared) GROUP BY custid ".$limiting);
			$switching = array(':loginID' => $loginID, ':shared' => $SHARED_ACCOUNTS);
			$withquotes = array(true, true);
		} else {
			$sql = Processwire\wire('database')->prepare("SELECT * FROM custindex WHERE shiptoid = '' GROUP BY custid " . $limiting);
			$switching = array(); $withquotes = array();
		}

		if ($debug) {
			return returnsqlquery($sql->queryString, $switching, $withquotes);
		} else {
			$sql->execute($switching);
			$sql->setFetchMode(PDO::FETCH_CLASS, 'Contact');
			return $sql->fetchAll();
		}
	}

	function count_distinctcustindex($loginID, $restrictions, $debug) {
		$SHARED_ACCOUNTS = Processwire\wire('config')->sharedaccounts;
		if ($restrictions) {
			$sql = Processwire\wire('database')->prepare("SELECT COUNT(DISTINCT(custid)) FROM custindex WHERE custid IN (SELECT DISTINCT(custid) FROM custperm WHERE loginid = :loginID OR loginid = :shared)");
			$switching = array(':loginID' => $loginID, ':shared' => $SHARED_ACCOUNTS);
			$withquotes = array(true, true);
		} else {
			$sql = Processwire\wire('database')->prepare("SELECT COUNT(DISTINCT(custid)) FROM custindex WHERE shiptoid = ''");
			$switching = array(); $withquotes = array();
		}

		if ($debug) {
			return returnsqlquery($sql->queryString, $switching, $withquotes);
		} else {
			$sql->execute($switching);
			return $sql->fetchColumn();
		}
	}
	
	function search_custindexpaged($loginID, $limit = 10, $page = 1, $restrictions, $keyword, $debug) {
		$SHARED_ACCOUNTS = DplusWire::wire('config')->sharedaccounts;
		$limiting = returnlimitstatement($limit, $page);
		$query = addslashes($keyword);
		$search = '%'.str_replace(' ', '%', str_replace('-', '', $query)).'%';
		$q = (new QueryBuilder())->table('custindex');
		
		if ($restrictions) {
			$permquery = (new QueryBuilder())->table('custperm');
			$permquery->field('custid, shiptoid');
			$permquery->where('loginid', [$loginID, $SHARED_ACCOUNTS]);
			$q->where('(custid, shiptoid)','in', $permquery);
		} else {
			
		}
		$fieldstring = implode(", ' ', ", array_keys(Contact::generate_classarray()));
		
		$q->where($q->expr("UCASE(REPLACE(CONCAT($fieldstring), '-', '')) LIKE UCASE([])", [$search]));
		$q->limit($limit, $q->generate_offset($page, $limit));
		
		if (DplusWire::wire('config')->cptechcustomer == 'stempf') {
			$q->group('custid, shiptoid');
			$q->order($q->expr('custid <> []', [$query]));
		} elseif (DplusWire::wire('config')->cptechcustomer == 'stat') {
			$q->group('custid');
		} else {
			$q->order($q->expr('custid <> []', [$query]));
		}
		$sql = DplusWire::wire('database')->prepare($q->render());
		if ($debug) {
			return $q->generate_sqlquery($q->params);
		} else {
			$sql->execute($q->params);
			$sql->setFetchMode(PDO::FETCH_CLASS, 'Contact');
			return $sql->fetchAll();
		}
	}
	
	function count_searchcustindex($loginID, $restrictions, $keyword, $debug) {
		$SHARED_ACCOUNTS = Processwire\wire('config')->sharedaccounts;
		$search = '%'.str_replace(' ', '%', str_replace('-', '', $keyword)).'%';

		if ($restrictions) {
			if (Processwire\wire('config')->cptechcustomer == 'stempf') {
				$sql = Processwire\wire('database')->prepare("SELECT COUNT(*) FROM (SELECT * FROM custindex GROUP BY custid, shiptoid) t WHERE (custid, shiptoid) IN (SELECT custid, shiptoid FROM custperm WHERE loginid = :loginID OR loginid = :shared) AND UCASE(REPLACE(CONCAT(custid, ' ', name, ' ', shiptoid, ' ', addr1, ' ', city, ' ', state, ' ', zip, ' ', phone, ' ', contact, ' ', source, ' ', extension), '-', '')) LIKE UCASE(:search)");
			} elseif (Processwire\wire('config')->cptechcustomer == 'stat') {
				$sql = Processwire\wire('database')->prepare("SELECT COUNT(*) FROM (SELECT * FROM custindex) t WHERE (custid, shiptoid) IN (SELECT custid, shiptoid FROM custperm WHERE loginid = :loginID OR loginid = :shared) AND UCASE(REPLACE(CONCAT(custid, ' ', name, ' ', shiptoid, ' ', addr1, ' ', city, ' ', state, ' ', zip, ' ', phone, ' ', contact, ' ', source, ' ', extension), '-', '')) LIKE UCASE(:search)");
			} else {
				$sql = Processwire\wire('database')->prepare("SELECT COUNT(*) FROM custindex WHERE (custid, shiptoid) IN (SELECT custid, shiptoid FROM custperm WHERE loginid = :loginID OR loginid = :shared) AND UCASE(REPLACE(CONCAT(custid, ' ', name, ' ', shiptoid, ' ', addr1, ' ', city, ' ', state, ' ', zip, ' ', phone, ' ', contact, ' ', source, ' ', extension), '-', '')) LIKE UCASE(:search)");
			}
			$switching = array(':loginID' => $loginID, ':shared' => $SHARED_ACCOUNTS, ':search' => $search);
			$withquotes = array(true, true, true, true);
		} else {
			if (Processwire\wire('config')->cptechcustomer == 'stempf') {
				$sql = Processwire\wire('database')->prepare("SELECT COUNT(*) FROM (SELECT * FROM custindex GROUP BY custid, shiptoid) t WHERE UCASE(REPLACE(CONCAT(custid, ' ', name, ' ', shiptoid, ' ', addr1, ' ', city, ' ', state, ' ', zip, ' ', phone, ' ', contact, ' ', source, ' ', extension), '-', '')) LIKE UCASE(:search)");
			} elseif (wire('config')->cptechcustomer == 'stat') {
				$sql = Processwire\wire('database')->prepare("SELECT COUNT(*) FROM (SELECT * FROM custindex) t WHERE UCASE(REPLACE(CONCAT(custid, ' ', name, ' ', shiptoid, ' ', addr1, ' ', city, ' ', state, ' ', zip, ' ', phone, ' ', contact, ' ', source, ' ', extension), '-', '')) LIKE UCASE(:search)");
			} else {
				$sql = Processwire\wire('database')->prepare("SELECT COUNT(*) FROM custindex WHERE UCASE(REPLACE(CONCAT(custid, ' ', name, ' ', shiptoid, ' ', addr1, ' ', city, ' ', state, ' ', zip, ' ', phone, ' ', contact, ' ', source, ' ', extension), '-', '')) LIKE UCASE(:search)");
			}
			$switching = array(':search' => $search); $withquotes = array(true);
		}

		if ($debug) {
			return returnsqlquery($sql->queryString, $switching, $withquotes);
		} else {
			$sql->execute($switching);
			return $sql->fetchColumn();
		}
	}

	function get_topxsellingcustomers($sessionID, $numberofcustomers, $debug = false) {
		$loginID = (Processwire\wire('user')->hascontactrestrictions) ? Processwire\wire('user')->loginid : 'admin';
		$q = (new QueryBuilder())->table('custperm');
		$q->where('loginid', $loginID);
		$q->where('shiptoid', '');
		$q->limit($numberofcustomers);
		$q->order('amountsold DESC');
		$sql = Processwire\wire('database')->prepare($q->render());

		if ($debug) {
			return $q->generate_sqlquery($q->params);
		} else {
			$sql->execute($q->params);
			return $sql->fetchAll(PDO::FETCH_ASSOC);
		}
	}

	function insert_newcustindexrecord($customer, $debug) { // DEPRECATED 3/5/2018
		$query = returninsertlinks($customer);
		$sql = Processwire\wire('database')->prepare("INSERT INTO custindex (".$query['columnlist'].") VALUES (".$query['valuelist'].")");
		$switching = $query['switching']; $withquotes = $query['withquotes'];
		if ($debug) {
			return returnsqlquery($sql->queryString, $switching, $withquotes);
		} else {
			$sql->execute($switching);
			return returnsqlquery($sql->queryString, $switching, $withquotes);
		}
	}
	
	function insert_customerindexrecord(Contact $customer, $debug = false) {
		$properties = array_keys($customer->_toArray());
		$q = (new QueryBuilder())->table('custindex');
		$q->mode('insert');
		
		foreach ($properties as $property) {
			if (!empty($customer->$property)) {
				$q->set($property, $customer->$property);
			}
		}
		$sql = Processwire\wire('database')->prepare($q->render());
		
		if ($debug) {
			return $q->generate_sqlquery();
		} else {
			$sql->execute($q->params);
			return $q->generate_sqlquery($q->params);
		}
	}
	
	function update_contact(Contact $contact, $debug = false) {
		$originalcontact = Contact::load($contact->custid, $contact->shiptoid, $contact->contact);
		$properties = array_keys($contact->_toArray());
		$q = (new QueryBuilder())->table('custindex');
		$q->mode('update');
		foreach ($properties as $property) {
			if ($contact->$property != $originalcontact->$property) {
				$q->set($property, $contact->$property);
			}
		}
		$q->where('custid', $contact->custid);
		$q->where('shiptoid', $contact->shiptoid);
		$q->where('contact', $contact->contact);
		$sql = Processwire\wire('database')->prepare($q->render());
		
		if ($debug) {
			return $q->generate_sqlquery();
		} else {
			if ($contact->has_changes()) {
				$sql->execute($q->params);
			}
			return $q->generate_sqlquery($q->params);
		}
	}
	
	function change_contactid(Contact $contact, $contactID, $debug = false) {
		$originalcontact = Contact::load($contact->custid, $contact->shiptoid, $contact->contact);
		$q = (new QueryBuilder())->table('custindex');
		$q->mode('update');
		$q->set('contact', $contactID);
		$q->where('custid', $contact->custid);
		$q->where('shiptoid', $contact->shiptoid);
		$q->where('contact', $contact->contact);
		$sql = Processwire\wire('database')->prepare($q->render());
		
		if ($debug) {
			return $q->generate_sqlquery();
		} else {
			if ($contact->has_changes()) {
				$sql->execute($q->params);
			}
			return $q->generate_sqlquery($q->params);
		}
	}

	function get_maxcustindexrecnbr($debug = false) {
		$q = (new QueryBuilder())->table('custindex');
		$q->field($q->expr('MAX(recno)'));
		$sql = Processwire\wire('database')->prepare($q->render());
		if ($debug) {
			return $q->generate_sqlquery();
		} else {
			$sql->execute();
			return $sql->fetchColumn();
		}
	}

	function change_custindexcustid($originalcustID, $newcustID, $debug = false) {
		$q = (new QueryBuilder())->table('custindex');
		$q->mode('update');
		$q->set('custid', $newcustID);
		$q->where('custid', substr($originalcustID, 0, 6));
		$sql = Processwire\wire('database')->prepare($q->render());
		if ($debug) {
			return $q->generate_sqlquery();
		} else {
			$sql->execute($q->params);
			return $q->generate_sqlquery();
		}
	}

/* =============================================================
	ORDERS FUNCTIONS
============================================================ */
	function count_userorders($sessionID, $filter = false, $filtertypes = false, $debug = false) {
		$q = (new QueryBuilder())->table('ordrhed');
		$expression = $q->expr('IF (COUNT(*) = 1, 1, IF(COUNT(DISTINCT(custid)) > 1, COUNT(*), 0)) as count');
		if (!empty($filter)) {
			$expression = $q->expr('COUNT(*)');
			$q->generate_filters($filter, $filtertypes);
		}
		$q->field($expression);
		$q->where('sessionid', $sessionID);
		$sql = Processwire\wire('database')->prepare($q->render());
		
		if ($debug) {
			return $q->generate_sqlquery($q->params);
		} else {
			$sql->execute($q->params);
			return $sql->fetchColumn();
		}
	}

	function get_userordersorderdate($sessionID, $limit = 10, $page = 1, $sortrule, $filter = false, $filtertypes = false, $useclass = false, $debug = false) {
		$q = (new QueryBuilder())->table('ordrhed');
		$q->field('ordrhed.*');
		$q->field($q->expr("STR_TO_DATE(orderdate, '%m/%d/%Y') as dateoforder"));
		$q->where('sessionid', $sessionID);
		$q->where('type', 'O');
		if (!empty($filter)) {
			$q->generate_filters($filter, $filtertypes);
		}
		$q->limit($limit, $q->generate_offset($page, $limit));
		$q->order('dateoforder ' . $sortrule);
		$sql = DplusWire::wire('database')->prepare($q->render());
		
		if ($debug) {
			return $q->generate_sqlquery($q->params);
		} else {
			$sql->execute($q->params);
			if ($useclass) {
				$sql->setFetchMode(PDO::FETCH_CLASS, 'SalesOrder');
				return $sql->fetchAll();
			}
			return $sql->fetchAll(PDO::FETCH_ASSOC);
		}
	}

	function get_userordersorderby($sessionID, $limit = 10, $page = 1, $sortrule, $orderby, $filter = false, $filtertypes = false, $useclass = false, $debug = false) {
		$q = (new QueryBuilder())->table('ordrhed');
		$q->field('ordrhed.*');
		$q->where('sessionid', $sessionID);
		$q->where('type', 'O');
		if (!empty($filter)) {
			$q->generate_filters($filter, $filtertypes);
		}
		$q->limit($limit, $q->generate_offset($page, $limit));
		$q->order($orderby .' '. $sortrule);
		$sql = DplusWire::wire('database')->prepare($q->render());
		
		if ($debug) {
			return $q->generate_sqlquery($q->params);
		} else {
			$sql->execute($q->params);
			if ($useclass) {
				$sql->setFetchMode(PDO::FETCH_CLASS, 'SalesOrder');
				return $sql->fetchAll();
			}
			return $sql->fetchAll(PDO::FETCH_ASSOC);
		}
	}

	function get_userorders($sessionID, $limit = 10, $page = 1, $filter = false, $filtertypes = false, $useclass = false, $debug = false) {
		$q = (new QueryBuilder())->table('ordrhed');
		$q->where('sessionid', $sessionID);
		$q->where('type', 'O');
		if (!empty($filter)) {
			$q->generate_filters($filter, $filtertypes);
		}
		$q->limit($limit, $q->generate_offset($page, $limit));
		$sql = DplusWire::wire('database')->prepare($q->render());
		
		if ($debug) {
			return $q->generate_sqlquery($q->params);
		} else {
			$sql->execute($q->params);
			if ($useclass) {
				$sql->setFetchMode(PDO::FETCH_CLASS, 'SalesOrder');
				return $sql->fetchAll();
			}
			return $sql->fetchAll(PDO::FETCH_ASSOC);
		}
	}

	function count_customerorders($sessionID, $custID, $shipID, $filter = false, $filtertypes = false, $debug) {
		$q = (new QueryBuilder())->table('ordrhed');
		$q->field($q->expr('COUNT(*) as count'));
		$q->where('sessionid', $sessionID);
		$q->where('custid', $custID);
		if (!empty($shipID)) {
			$q->where('shiptoid', $shipID);
		}
		$q->where('type', 'O');
		if (!empty($filter)) {
			$q->generate_filters($filter, $filtertypes);
		}
		$sql = DplusWire::wire('database')->prepare($q->render());
		
		if ($debug) {
			return $q->generate_sqlquery($q->params);
		} else {
			$sql->execute($q->params);
			return $sql->fetchColumn();
		}
	}

	function get_customerorders($sessionID, $custID, $shipID, $limit = 10, $page = 1, $filter = false, $filtertypes = false, $useclass = false, $debug = false) {
		$q = (new QueryBuilder())->table('ordrhed');
		$q->field('ordrhed.*');
		$q->where('sessionid', $sessionID);
		$q->where('custid', $custID);
		if (!empty($shipID)) {
			$q->where('shiptoid', $shipID);
		}
		$q->where('type', 'O');
		if (!empty($filter)) {
			$q->generate_filters($filter, $filtertypes);
		}
		$q->limit($limit, $q->generate_offset($page, $limit));
		$sql = DplusWire::wire('database')->prepare($q->render());
		
		if ($debug) {
			return $q->generate_sqlquery($q->params);
		} else {
			$sql->execute($q->params);
			if ($useclass) {
				$sql->setFetchMode(PDO::FETCH_CLASS, 'SalesOrder');
				return $sql->fetchAll();
			}
			return $sql->fetchAll(PDO::FETCH_ASSOC);
		}
	}

	function get_customerordersorderby($sessionID, $custID, $shipID, $limit = 10, $page = 1, $sortrule, $orderby, $filter = false, $filtertypes = false, $useclass = false, $debug = false) {
		$q = (new QueryBuilder())->table('ordrhed');
		$q->field('ordrhed.*');
		$q->where('sessionid', $sessionID);
		$q->where('custid', $custID);
		if (!empty($shipID)) {
			$q->where('shiptoid', $shipID);
		}
		$q->where('type', 'O');
		if (!empty($filter)) {
			$q->generate_filters($filter, $filtertypes);
		}
		$q->limit($limit, $q->generate_offset($page, $limit));
		$q->order($orderby .' '. $sortrule);
		$sql = DplusWire::wire('database')->prepare($q->render());
		
		if ($debug) {
			return $q->generate_sqlquery($q->params);
		} else {
			$sql->execute($q->params);
			if ($useclass) {
				$sql->setFetchMode(PDO::FETCH_CLASS, 'SalesOrder');
				return $sql->fetchAll();
			}
			return $sql->fetchAll();
		}
	}

	function get_customerordersorderdate($sessionID, $custID, $shipID, $limit = 10, $page = 1, $sortrule, $filter = false, $filtertypes = false, $useclass = false, $debug) {
		$q = (new QueryBuilder())->table('ordrhed');
		$q->field('ordrhed.*');
		$q->field($q->expr("STR_TO_DATE(orderdate, '%m/%d/%Y') as dateoforder"));
		$q->where('sessionid', $sessionID);
		$q->where('custid', $custID);
		if (!empty($shipID)) {
			$q->where('shiptoid', $shipID);
		}
		$q->where('type', 'O');
		if (!empty($filter)) {
			$q->generate_filters($filter, $filtertypes);
		}
		$q->limit($limit, $q->generate_offset($page, $limit));
		$q->order('dateoforder ' . $sortrule);
		$sql = DplusWire::wire('database')->prepare($q->render());
		if ($debug) {
			return $q->generate_sqlquery($q->params);
		} else {
			$sql->execute($q->params);
			if ($useclass) {
				$sql->setFetchMode(PDO::FETCH_CLASS, 'SalesOrder');
				return $sql->fetchAll();
			}
			return $sql->fetchAll(PDO::FETCH_ASSOC);
		}
	}
	
	function get_custidfromorder($sessionID, $ordn, $debug = false) {
		$q = (new QueryBuilder())->table('ordrhed');
		$q->field('custid');
		$q->where('sessionid', $sessionID);
		$q->where('orderno', $ordn);
		$sql = DplusWire::wire('database')->prepare($q->render());
		
		if ($debug) {
			return $q->generate_sqlquery($q->params);
		} else {
			$sql->execute($q->params);
			return $sql->fetchColumn();
		}
	}
	
	function get_shiptoidfromorder($sessionID, $ordn, $debug = false) {
		$q = (new QueryBuilder())->table('ordrhed');
		$q->field('shiptoid');
		$q->where('sessionid', $sessionID);
		$q->where('orderno', $ordn);
		$sql = DplusWire::wire('database')->prepare($q->render());
		
		if ($debug) {
			return $q->generate_sqlquery($q->params);
		} else {
			$sql->execute($q->params);
			return $sql->fetchColumn();
		}
	}

	function get_maxordertotal($sessionID, $custID = false, $shipID = false, $debug = false) {
		$q = (new QueryBuilder())->table('ordrhed');
		$q->field($q->expr("MAX(CAST(ordertotal AS DECIMAL(8,2))) AS ordertotal"));
		$q->where('sessionid', $sessionID);
		if ($custID) {
			$q->where('custid', $custID);
		}
		if ($shipID) {
			$q->where('shiptoid', $shipID);
		}
		$sql = DplusWire::wire('database')->prepare($q->render());
		if ($debug) {
			return $q->generate_sqlquery($q->params);
		} else {
			$sql->execute($q->params);
			return $sql->fetchColumn();
		}
	}

	function get_minordertotal($sessionID, $custID = false, $shipID = false, $debug = false) {
		$q = (new QueryBuilder())->table('ordrhed');
		$q->field($q->expr("MIN(CAST(ordertotal AS DECIMAL(8,2))) AS ordertotal"));
		$q->where('sessionid', $sessionID);
		if ($custID) {
			$q->where('custid', $custID);
		}
		if ($shipID) {
			$q->where('shiptoid', $shipID);
		}
		$sql = DplusWire::wire('database')->prepare($q->render());
		if ($debug) {
			return $q->generate_sqlquery($q->params);
		} else {
			$sql->execute($q->params);
			return $sql->fetchColumn();
		}
	}

	function get_minorderdate($sessionID, $field, $custID = false, $shipID = false, $debug = false) {
		$q = (new QueryBuilder())->table('ordrhed');
		$q->field($q->expr("MIN(STR_TO_DATE($field, '%m/%d/%Y'))"));
		$q->where('sessionid', $sessionID);
		if ($custID) {
			$q->where('custid', $custID);
		}
		if ($shipID) {
			$q->where('shiptoid', $shipID);
		}
		$sql = DplusWire::wire('database')->prepare($q->render());
		
		if ($debug) {
			return $q->generate_sqlquery($q->params);
		} else {
			$sql->execute($q->params);
			return $sql->fetchColumn();
		}
	}

	function get_orderdetails($sessionID, $ordn, $useclass = false, $debug) {
		$sql = DplusWire::wire('database')->prepare("SELECT * FROM ordrdet WHERE sessionid = :sessionID AND orderno = :ordn");
		$switching = array(':sessionID' => $sessionID, ':ordn' => $ordn); $withquotes = array(true, true);
		if ($debug) {
			return returnsqlquery($sql->queryString, $switching, $withquotes);
		} else {
			$sql->execute($switching);
			if ($useclass) {
				$sql->setFetchMode(PDO::FETCH_CLASS, 'SalesOrderDetail');
				return $sql->fetchAll();
			}
			return $sql->fetchAll(PDO::FETCH_ASSOC);
		}
	}

	function hasanorderlocked($sessionID) {
		$sql = Processwire\wire('database')->prepare("SELECT COUNT(*) FROM ordlock WHERE sessionid = :sessionID");
		$switching = array(':sessionID' => $sessionID);
		$sql->execute($switching);
		return $sql->fetchColumn() > 0 ? true : false;
	}

	function getlockedordn($sessionID) {
		$sql = Processwire\wire('database')->prepare("SELECT orderno FROM ordlock WHERE sessionid = :sessionID LIMIT 1");
		$switching = array(':sessionID' => $sessionID);
		$sql->execute($switching);
		return $sql->fetchColumn();
	}

	function is_orderlocked($sessionID, $ordn) {
		$sql = Processwire\wire('database')->prepare("SELECT COUNT(*) FROM ordlock WHERE sessionid = :sessionID AND orderno = :ordn LIMIT 1");
		$switching = array(':sessionID' => $sessionID, ':ordn' => $ordn);
		$sql->execute($switching);
		return $sql->fetchColumn();
	}
	
	function get_nextorderlock($sessionID) {
		$sql = Processwire\wire('database')->prepare("SELECT MAX(recno) FROM ordlock WHERE sessionid = :sessionID LIMIT 1");
		$switching = array(':sessionID' => $sessionID);
		$sql->execute($switching);
		return (intval($sql->fetchColumn()) + 1);
	}

	function get_orderdocs($sessionID, $ordn, $debug = false) {
		$q = (new QueryBuilder())->table('orddocs');
		$q->where('sessionid', $sessionID);
		$q->where('orderno', $ordn);
		$q->where('itemnbr', '');
		$sql = DplusWire::wire('database')->prepare($q->render());
		
		if ($debug) {
			return $q->generate_sqlquery($q->params);
		} else {
			$sql->execute($q->params);
			return $sql->fetchAll(PDO::FETCH_ASSOC);
		}
	}
	
/* =============================================================
	SALES HISTORY FUNCTIONS
============================================================ */
	function is_ordersaleshistory($ordn, $debug = false) {
		$q = (new QueryBuilder())->table('saleshist');
		$q->field('COUNT(*)');
		$q->where('orderno', $ordn);
		$sql = DplusWire::wire('database')->prepare($q->render());
		
		if ($debug) {
			return $q->generate_sqlquery($q->params);
		} else {
			$sql->execute($q->params);
			return $sql->fetchColumn();
		}
	}
	
	function get_custidfromsaleshistory($ordn, $debug = false) {
		$q = (new QueryBuilder())->table('saleshist');
		$q->field('custid');
		$q->where('orderno', $ordn);
		$sql = DplusWire::wire('database')->prepare($q->render());
		
		if ($debug) {
			return $q->generate_sqlquery($q->params);
		} else {
			$sql->execute($q->params);
			return $sql->fetchColumn();
		}
	}
	
	function get_minsaleshistoryorderdate($sessionID, $field, $custID = false, $shipID = false, $debug = false) {
		$q = (new QueryBuilder())->table('saleshist');
		$q->field($q->expr("MIN(STR_TO_DATE(CAST($field as CHAR(12)), '%Y%m%d'))"));
		if ($custID) {
			$q->where('custid', $custID);
		}
		if ($shipID) {
			$q->where('shiptoid', $shipID);
		}
		$sql = DplusWire::wire('database')->prepare($q->render());
		
		if ($debug) {
			return $q->generate_sqlquery($q->params);
		} else {
			$sql->execute($q->params);
			return $sql->fetchColumn();
		}
	}
	
	function get_maxsaleshistoryordertotal($sessionID, $custID = false, $shipID = false, $debug = false) {
		$q = (new QueryBuilder())->table('saleshist');
		$q->field($q->expr("MAX(ordertotal)"));
		if ($custID) {
			$q->where('custid', $custID);
		}
		if ($shipID) {
			$q->where('shiptoid', $shipID);
		}
		$sql = DplusWire::wire('database')->prepare($q->render());
		if ($debug) {
			return $q->generate_sqlquery($q->params);
		} else {
			$sql->execute($q->params);
			return $sql->fetchColumn();
		}
	}
	
	function get_minsaleshistoryordertotal($sessionID, $custID = false, $shipID = false, $debug = false) {
		$q = (new QueryBuilder())->table('saleshist');
		$q->field($q->expr("MIN(ordertotal)"));
		if ($custID) {
			$q->where('custid', $custID);
		}
		if ($shipID) {
			$q->where('shiptoid', $shipID);
		}
		$sql = DplusWire::wire('database')->prepare($q->render());
		if ($debug) {
			return $q->generate_sqlquery($q->params);
		} else {
			$sql->execute($q->params);
			return $sql->fetchColumn();
		}
	}
	
	function count_usersaleshistory($sessionID, $filter = false, $filtertypes = false, $debug = false) {
		$q = (new QueryBuilder())->table('saleshist');
		$q->field('COUNT(*)');
		
		if (DplusWire::wire('user')->hascontactrestrictions) {
			$q->where('sp1', DplusWire::wire('user')->salespersonid);
		}
		if (!empty($filter)) {
			$q->generate_filters($filter, $filtertypes);
		}
		$sql = DplusWire::wire('database')->prepare($q->render());
		
		if ($debug) {
			return $q->generate_sqlquery($q->params);
		} else {
			$sql->execute($q->params);
			return $sql->fetchColumn();
		}
	}
	
	function get_usersaleshistory($sessionID, $limit = 10, $page = 1, $filter = false, $filtertypes = false, $useclass = false, $debug = false) {
		$q = (new QueryBuilder())->table('saleshist');
		
		if (DplusWire::wire('user')->hascontactrestrictions) {
			$q->where('sp1', DplusWire::wire('user')->salespersonid);
		}
		if (!empty($filter)) {
			$q->generate_filters($filter, $filtertypes);
		}
		$q->limit($limit, $q->generate_offset($page, $limit));
		$sql = DplusWire::wire('database')->prepare($q->render());
		
		if ($debug) {
			return $q->generate_sqlquery($q->params);
		} else {
			$sql->execute($q->params);
			if ($useclass) {
				$sql->setFetchMode(PDO::FETCH_CLASS, 'SalesOrderHistory');
				return $sql->fetchAll();
			}
			return $sql->fetchAll(PDO::FETCH_ASSOC);
		}
	}
	
	function get_usersaleshistoryorderby($sessionID, $limit = 10, $page = 1, $sortrule, $orderby, $filter = false, $filtertypes = false, $useclass = false, $debug = false) {
		$q = (new QueryBuilder())->table('saleshist');
		if (DplusWire::wire('user')->hascontactrestrictions) {
			$q->where('sp1', DplusWire::wire('user')->salespersonid);
		}
		if (!empty($filter)) {
			$q->generate_filters($filter, $filtertypes);
		}
		$q->order($orderby .' '. $sortrule);
		$q->limit($limit, $q->generate_offset($page, $limit));
		
		$sql = DplusWire::wire('database')->prepare($q->render());
		
		if ($debug) {
			return $q->generate_sqlquery($q->params);
		} else {
			$sql->execute($q->params);
			if ($useclass) {
				$sql->setFetchMode(PDO::FETCH_CLASS, 'SalesOrderHistory');
				return $sql->fetchAll();
			}
			return $sql->fetchAll(PDO::FETCH_ASSOC);
		}
	}
	
	function get_usersaleshistoryinvoicedate($sessionID, $limit = 10, $page = 1, $sortrule, $filter = false, $filtertypes = false, $useclass = false, $debug = false) {
		$q = (new QueryBuilder())->table('saleshist');
		$q->field('saleshist.*');
		$q->field($q->expr("STR_TO_DATE(invdate, '%Y%m%d') as dateofinvoice"));
		
		if (DplusWire::wire('user')->hascontactrestrictions) {
			$q->where('sp1', DplusWire::wire('user')->salespersonid);
		}
		if (!empty($filter)) {
			$q->generate_filters($filter, $filtertypes);
		}
		$q->order('dateofinvoice ' . $sortrule);
		$q->limit($limit, $q->generate_offset($page, $limit));
		$sql = DplusWire::wire('database')->prepare($q->render());
		
		if ($debug) {
			return $q->generate_sqlquery($q->params);
		} else {
			$sql->execute($q->params);
			if ($useclass) {
				$sql->setFetchMode(PDO::FETCH_CLASS, 'SalesOrderHistory');
				return $sql->fetchAll();
			}
			return $sql->fetchAll(PDO::FETCH_ASSOC);
		}
	}
	
	function get_usersaleshistoryorderdate($sessionID, $limit = 10, $page = 1, $sortrule, $filter = false, $filtertypes = false, $useclass = false, $debug = false) {
		$q = (new QueryBuilder())->table('saleshist');
		$q->field('saleshist.*');
		$q->field($q->expr("STR_TO_DATE(orderdate, '%Y%m%d') as dateoforder"));
		if (DplusWire::wire('user')->hascontactrestrictions) {
			$q->where('sp1', DplusWire::wire('user')->salespersonid);
		}
		if (!empty($filter)) {
			$q->generate_filters($filter, $filtertypes);
		}
		$q->order('dateoforder ' . $sortrule);
		$q->limit($limit, $q->generate_offset($page, $limit));
		$sql = DplusWire::wire('database')->prepare($q->render());
		
		if ($debug) {
			return $q->generate_sqlquery($q->params);
		} else {
			$sql->execute($q->params);
			if ($useclass) {
				$sql->setFetchMode(PDO::FETCH_CLASS, 'SalesOrderHistory');
				return $sql->fetchAll();
			}
			return $sql->fetchAll(PDO::FETCH_ASSOC);
		}
	}
	
	function count_customersaleshistory($sessionID, $custID, $shiptoID = '', $filter = false, $filtertypes = false, $debug = false) {
		$q = (new QueryBuilder())->table('saleshist');
		$q->field('COUNT(*)');
		$q->where('custid', $custID);
		
		if (!empty($shiptoID)) {
			$q->where('shiptoid', $shiptoID);
		}
		
		if (DplusWire::wire('user')->hascontactrestrictions) {
			$q->where('sp1', DplusWire::wire('user')->salespersonid);
		}
		if (!empty($filter)) {
			$q->generate_filters($filter, $filtertypes);
		}
		
		$sql = DplusWire::wire('database')->prepare($q->render());
		
		if ($debug) {
			return $q->generate_sqlquery($q->params);
		} else {
			$sql->execute($q->params);
			return $sql->fetchColumn();
		}
	}
	
	function get_customersaleshistory($sessionID, $custID, $shiptoID = '', $limit = 10, $page = 1, $filter = false, $filtertypes = false, $useclass = false, $debug = false) {
		$q = (new QueryBuilder())->table('saleshist');
		$q->where('custid', $custID);
		
		if (!empty($shiptoID)) {
			$q->where('shiptoid', $shiptoID);
		}
		if (DplusWire::wire('user')->hascontactrestrictions) {
			$q->where('sp1', DplusWire::wire('user')->salespersonid);
		}
		if (!empty($filter)) {
			$q->generate_filters($filter, $filtertypes);
		}
		$q->limit($limit, $q->generate_offset($page, $limit));
		$sql = DplusWire::wire('database')->prepare($q->render());
		
		if ($debug) {
			return $q->generate_sqlquery($q->params);
		} else {
			$sql->execute($q->params);
			if ($useclass) {
				$sql->setFetchMode(PDO::FETCH_CLASS, 'SalesOrderHistory');
				return $sql->fetchAll();
			}
			return $sql->fetchAll(PDO::FETCH_ASSOC);
		}
	}
	
	function get_customersaleshistoryorderby($sessionID, $custID, $shiptoID = '', $limit = 10, $page = 1, $sortrule, $orderby, $filter = false, $filtertypes = false, $useclass = false, $debug = false) {
		$q = (new QueryBuilder())->table('saleshist');
		$q->where('custid', $custID);
		
		if (!empty($shiptoID)) {
			$q->where('shiptoid', $shiptoID);
		}
		if (DplusWire::wire('user')->hascontactrestrictions) {
			$q->where('sp1', DplusWire::wire('user')->salespersonid);
		}
		if (!empty($filter)) {
			$q->generate_filters($filter, $filtertypes);
		}
		$q->order($orderby .' '. $sortrule);
		$q->limit($limit, $q->generate_offset($page, $limit));
		$sql = DplusWire::wire('database')->prepare($q->render());
		
		if ($debug) {
			return $q->generate_sqlquery($q->params);
		} else {
			$sql->execute($q->params);
			if ($useclass) {
				$sql->setFetchMode(PDO::FETCH_CLASS, 'SalesOrderHistory');
				return $sql->fetchAll();
			}
			return $sql->fetchAll(PDO::FETCH_ASSOC);
		}
	}
	
	function get_customersaleshistoryinvoicedate($sessionID, $custID, $shiptoID = '', $limit = 10, $page = 1, $sortrule, $filter = false, $filtertypes = false, $useclass = false, $debug = false) {
		$q = (new QueryBuilder())->table('saleshist');
		$q->field('saleshist.*');
		$q->field($q->expr("STR_TO_DATE(invdate, '%Y%m%d') as dateofinvoice"));
		$q->where('custid', $custID);
		
		if (!empty($shiptoID)) {
			$q->where('shiptoid', $shiptoID);
		}
		if (DplusWire::wire('user')->hascontactrestrictions) {
			$q->where('sp1', DplusWire::wire('user')->salespersonid);
		}
		if (!empty($filter)) {
			$q->generate_filters($filter, $filtertypes);
		}
		$q->order('dateofinvoice ' . $sortrule);
		$q->limit($limit, $q->generate_offset($page, $limit));
		$sql = DplusWire::wire('database')->prepare($q->render());
		
		if ($debug) {
			return $q->generate_sqlquery($q->params);
		} else {
			$sql->execute($q->params);
			if ($useclass) {
				$sql->setFetchMode(PDO::FETCH_CLASS, 'SalesOrderHistory');
				return $sql->fetchAll();
			}
			return $sql->fetchAll(PDO::FETCH_ASSOC);
		}
	}
	
	function get_customersaleshistoryorderdate($sessionID, $custID, $shiptoID = '', $limit = 10, $page = 1, $sortrule, $filter = false, $filtertypes = false, $useclass = false, $debug = false) {
		$q = (new QueryBuilder())->table('saleshist');
		$q->field('saleshist.*');
		$q->field($q->expr("STR_TO_DATE(orderdate, '%Y%m%d') as dateoforder"));
		$q->where('custid', $custID);
		
		if (!empty($shiptoID)) {
			$q->where('shiptoid', $shiptoID);
		}
		if (DplusWire::wire('user')->hascontactrestrictions) {
			$q->where('sp1', DplusWire::wire('user')->salespersonid);
		}
		if (!empty($filter)) {
			$q->generate_filters($filter, $filtertypes);
		}
		$q->order('dateoforder ' . $sortrule);
		$q->limit($limit, $q->generate_offset($page, $limit));
		$sql = DplusWire::wire('database')->prepare($q->render());
		
		if ($debug) {
			return $q->generate_sqlquery($q->params);
		} else {
			$sql->execute($q->params);
			if ($useclass) {
				$sql->setFetchMode(PDO::FETCH_CLASS, 'SalesOrderHistory');
				return $sql->fetchAll();
			}
			return $sql->fetchAll(PDO::FETCH_ASSOC);
		}
	}
/* =============================================================
	QUOTES FUNCTIONS
============================================================ */
	function hasaquotelocked($sessionID) {
		$sql = Processwire\wire('database')->prepare("SELECT COUNT(*) FROM quotelock WHERE sessionid = :sessionID");
		$switching = array(':sessionID' => $sessionID); $withquotes = array(true);
		$sql->execute($switching);
		return $sql->fetchColumn();
	}

	function getlockedquotenbr($sessionID) {
		$sql = Processwire\wire('database')->prepare("SELECT quotenbr FROM quotelock WHERE sessionid = :sessionID");
		$switching = array(':sessionID' => $sessionID); $withquotes = array(true);
		$sql->execute($switching);
		return $sql->fetchColumn();
	}

	function caneditquote($sessionID, $qnbr) {
		$sql = Processwire\wire('database')->prepare("SELECT COUNT(*) FROM quotelock WHERE sessionid = :sessionID AND quotenbr = :qnbr");
		$switching = array(':sessionID' => $sessionID, ':qnbr' => $qnbr);
		$sql->execute($switching);
		return $sql->fetchColumn();
	}
	
	function count_userquotes($sessionID, $filter = false, $filtertypes = false, $debug = false) {
		$q = (new QueryBuilder())->table('quothed');
		$expression = $q->expr('IF (COUNT(*) = 1, 1, IF(COUNT(DISTINCT(custid)) > 1, COUNT(*), 0)) as count');
		if (!empty($filter)) {
			$expression = $q->expr('COUNT(*)');
			$q->generate_filters($filter, $filtertypes);
		}
		$q->field($expression);
		$q->where('sessionid', $sessionID);
		if (!empty($filter)) {
			$q->generate_filters($filter, $filtertypes);
		}
		$sql = Processwire\wire('database')->prepare($q->render());
		
		if ($debug) {
			return $q->generate_sqlquery($q->params);
		} else {
			$sql->execute($q->params);
			return $sql->fetchColumn();
		}
	}
	
	function get_maxquotetotal($sessionID, $custID = false, $shipID = false, $debug = false) {
		$q = (new QueryBuilder())->table('quothed');
		$q->field($q->expr("MAX(CAST(subtotal AS DECIMAL(8,2))) AS subtotal"));
		$q->where('sessionid', $sessionID);
		if ($custID) {
			$q->where('custid', $custID);
		}
		if ($shipID) {
			$q->where('shiptoid', $shipID);
		}
		$sql = DplusWire::wire('database')->prepare($q->render());
		if ($debug) {
			return $q->generate_sqlquery($q->params);
		} else {
			$sql->execute($q->params);
			return $sql->fetchColumn();
		}
	}
	
	function get_minquotetotal($sessionID, $custID = false, $shipID = false, $debug = false) {
		$q = (new QueryBuilder())->table('quothed');
		$q->field($q->expr("MIN(CAST(subtotal AS DECIMAL(8,2))) AS subtotal"));
		
		$q->where('sessionid', $sessionID);
		if ($custID) {
			$q->where('custid', $custID);
		}
		if ($shipID) {
			$q->where('shiptoid', $shipID);
		}
		$sql = DplusWire::wire('database')->prepare($q->render());
		if ($debug) {
			return $q->generate_sqlquery($q->params);
		} else {
			$sql->execute($q->params);
			return $sql->fetchColumn();
		}
	}
	
	function get_minquotedate($sessionID, $field, $custID = false, $debug = false) {
		$q = (new QueryBuilder())->table('quothed');
		$q->field($q->expr("MIN(STR_TO_DATE($field, '%m/%d/%Y'))"));
		$q->where('sessionid', $sessionID);
		if ($custID) {
			$q->where('custid', $custID);
		}
		$sql = Processwire\wire('database')->prepare($q->render());
		
		if ($debug) {
			return $q->generate_sqlquery($q->params);
		} else {
			$sql->execute($q->params);
			return $sql->fetchColumn();
		}
	}
	
	function get_userquotes($sessionID, $limit, $page = 1, $filter = false, $filtertypes = false, $useclass = false, $debug = false) {
		$q = (new QueryBuilder())->table('quothed');
		$q->field('quothed.*');
		$q->field($q->expr("CAST(subtotal AS DECIMAL(8,2)) AS subtotal"));
		$q->where('sessionid', $sessionID);
		if (!empty($filter)) {
			$q->generate_filters($filter, $filtertypes);
		}
		$q->limit($limit, $q->generate_offset($page, $limit));
		$sql = Processwire\wire('database')->prepare($q->render());
		
		if ($debug) {
			return $q->generate_sqlquery($q->params);
		} else {
			$sql->execute($q->params);
			if ($useclass) {
				$sql->setFetchMode(PDO::FETCH_CLASS, 'Quote');
				return $sql->fetchAll();
			}
			return $sql->fetchAll(PDO::FETCH_ASSOC);
		}
	}
	
	function get_userquotesquotedate($sessionID, $limit = 10, $page = 1, $sortrule, $filter = false, $filtertypes = false, $useclass = false, $debug = false) {
		$q = (new QueryBuilder())->table('quothed');
		$q->field('quothed.*');
		$q->field($q->expr("STR_TO_DATE(quotdate, '%m/%d/%Y') as quotedate"));
		$q->field($q->expr("CAST(subtotal AS DECIMAL(8,2)) AS subtotal"));
		$q->where('sessionid', $sessionID);
		if (!empty($filter)) {
			$q->generate_filters($filter, $filtertypes);
		}
		$q->limit($limit, $q->generate_offset($page, $limit));
		$q->order('quotedate', $sortrule);
		$sql = Processwire\wire('database')->prepare($q->render());
		
		if ($debug) {
			return $q->generate_sqlquery($q->params);
		} else {
			$sql->execute($q->params);
			if ($useclass) {
				$sql->setFetchMode(PDO::FETCH_CLASS, 'Quote');
				return $sql->fetchAll();
			}
			return $sql->fetchAll(PDO::FETCH_ASSOC);
		}
	}
	
	function get_userquotesrevdate($sessionID, $limit = 10, $page = 1, $sortrule, $filter = false, $filtertypes = false, $useclass = false, $debug = false) {
		$q = (new QueryBuilder())->table('quothed');
		$q->field('quothed.*');
		$q->field($q->expr("STR_TO_DATE(revdate, '%m/%d/%Y') as reviewdate"));
		$q->where('sessionid', $sessionID);
		if (!empty($filter)) {
			$q->generate_filters($filter, $filtertypes);
		}
		$q->limit($limit, $q->generate_offset($page, $limit));
		$q->order('reviewdate', $sortrule);
		$sql = Processwire\wire('database')->prepare($q->render());
		
		if ($debug) {
			return $q->generate_sqlquery($q->params);
		} else {
			$sql->execute($q->params);
			if ($useclass) {
				$sql->setFetchMode(PDO::FETCH_CLASS, 'Quote');
				return $sql->fetchAll();
			}
			return $sql->fetchAll(PDO::FETCH_ASSOC);
		}
	}
		
	function get_userquotesexpdate($sessionID, $limit = 10, $page = 1, $sortrule, $filter = false, $filtertypes = false, $useclass = false, $debug = false) {
		$q = (new QueryBuilder())->table('quothed');
		$q->field('quothed.*');
		$q->field($q->expr("STR_TO_DATE(expdate, '%m/%d/%Y') as expiredate"));
		$q->field($q->expr("CAST(subtotal AS DECIMAL(8,2)) AS subtotal"));
		$q->where('custid', $custID);
		if (!empty($filter)) {
			$q->generate_filters($filter, $filtertypes);
		}
		$q->limit($limit, $q->generate_offset($page, $limit));
		$q->order('expiredate', $sortrule);
		$sql = Processwire\wire('database')->prepare($q->render());
		
		if ($debug) {
			return $q->generate_sqlquery($q->params);
		} else {
			$sql->execute($q->params);
			if ($useclass) {
				$sql->setFetchMode(PDO::FETCH_CLASS, 'Quote');
				return $sql->fetchAll();
			}
			return $sql->fetchAll(PDO::FETCH_ASSOC);
		}
	}
	
	function get_userquotesorderby($sessionID, $limit = 10, $page = 1, $sortrule, $orderby, $filter = false, $filtertypes = false, $useclass = true, $debug = false) {
		$q = (new QueryBuilder())->table('quothed');
		$q->field('quothed.*');
		$q->field($q->expr("CAST(subtotal AS DECIMAL(8,2)) AS subtotal"));
		$q->where('sessionid', $sessionID);
		if (!empty($filter)) {
			$q->generate_filters($filter, $filtertypes);
		}
		$q->limit($limit, $q->generate_offset($page, $limit));
		$q->order($orderby, $sortrule);
		$sql = DplusWire::wire('database')->prepare($q->render());
		
		if ($debug) {
			return $q->generate_sqlquery($q->params);
		} else {
			$sql->execute($q->params);
			if ($useclass) {
				$sql->setFetchMode(PDO::FETCH_CLASS, 'Quote');
				return $sql->fetchAll();
			}
			return $sql->fetchAll(PDO::FETCH_ASSOC);
		}
	}

	function count_customerquotes($sessionID, $custID, $shipID, $filter = false, $filtertypes = false, $debug = false) {
		$q = (new QueryBuilder())->table('quothed');
		$q->field('COUNT(*)');
		$q->where('sessionid', $sessionID);
		$q->where('custid', $custID);
		if (!empty($shipID)) {
			$q->where('shiptoid', $shipID);
		}
		if (!empty($filter)) {
			$q->generate_filters($filter, $filtertypes);
		}
		$sql = DplusWire::wire('database')->prepare($q->render());
		
		if ($debug) {
			return $q->generate_sqlquery($q->params);
		} else {
			$sql->execute($q->params);
			return $sql->fetchColumn();
		}
	}

	function get_customerquotes($sessionID, $custID, $shipID, $limit = 10, $page = 1, $filter = false, $filtertypes = false, $useclass = false, $debug = false) {
		$q = (new QueryBuilder())->table('quothed');
		$q->where('sessionid', $sessionID);
		$q->where('custid', $custID);
		if (!empty($shipID)) {
			$q->where('shiptoid', $shipID);
		}
		if (!empty($filter)) {
			$q->generate_filters($filter, $filtertypes);
		}
		$q->limit($limit, $q->generate_offset($page, $limit));
		$sql = DplusWire::wire('database')->prepare($q->render());
		
		if ($debug) {
			return $q->generate_sqlquery($q->params);
		} else {
			$sql->execute($q->params);
			if ($useclass) {
				$sql->setFetchMode(PDO::FETCH_CLASS, 'Quote');
				return $sql->fetchAll();
			}
			return $sql->fetchAll(PDO::FETCH_ASSOC);
		}
	}
	
	function get_customerquotesquotedate($sessionID, $custID, $shipID, $limit = 10, $page = 1, $sortrule, $filter = false, $filtertypes = false, $useclass = false, $debug = false) {
		$q = (new QueryBuilder())->table('quothed');
		$q->field('quothed.*');
		$q->field($q->expr("STR_TO_DATE(quotdate, '%m/%d/%Y') as quotedate"));
		$q->where('sessionid', $sessionID);
		$q->where('custid', $custID);
		if (!empty($shipID)) {
			$q->where('shiptoid', $shipID);
		}
		if (!empty($filter)) {
			$q->generate_filters($filter, $filtertypes);
		}
		$q->limit($limit, $q->generate_offset($page, $limit));
		$q->order('quotedate', $sortrule);
		$sql = DplusWire::wire('database')->prepare($q->render());
		
		if ($debug) {
			return $q->generate_sqlquery($q->params);
		} else {
			$sql->execute($q->params);
			if ($useclass) {
				$sql->setFetchMode(PDO::FETCH_CLASS, 'Quote');
				return $sql->fetchAll();
			}
			return $sql->fetchAll(PDO::FETCH_ASSOC);
		}
	}
	
	function get_customerquotesrevdate($sessionID, $custID, $shipID, $limit = 10, $page = 1, $sortrule, $filter = false, $filtertypes = false, $useclass = false, $debug = false) {
		$q = (new QueryBuilder())->table('quothed');
		$q->field('quothed.*');
		$q->field($q->expr("STR_TO_DATE(revdate, '%m/%d/%Y') as reviewdate"));
		$q->where('sessionid', $sessionID);
		$q->where('custid', $custID);
		if (!empty($shipID)) {
			$q->where('shiptoid', $shipID);
		}
		if (!empty($filter)) {
			$q->generate_filters($filter, $filtertypes);
		}
		$q->limit($limit, $q->generate_offset($page, $limit));
		$q->order('reviewdate', $sortrule);
		$sql = DplusWire::wire('database')->prepare($q->render());
		
		if ($debug) {
			return $q->generate_sqlquery($q->params);
		} else {
			$sql->execute($q->params);
			if ($useclass) {
				$sql->setFetchMode(PDO::FETCH_CLASS, 'Quote');
				return $sql->fetchAll();
			}
			return $sql->fetchAll(PDO::FETCH_ASSOC);
		}
	}
		
	function get_customerquotesexpdate($sessionID, $custID, $shipID, $limit = 10, $page = 1, $sortrule, $filter = false, $filtertypes = false, $useclass = false, $debug = false) {
		$q = (new QueryBuilder())->table('quothed');
		$q->field('quothed.*');
		$q->field($q->expr("STR_TO_DATE(expdate, '%m/%d/%Y') as expiredate"));
		$q->where('sessionid', $sessionID);
		$q->where('custid', $custID);
		if (!empty($shipID)) {
			$q->where('shiptoid', $shipID);
		}
		if (!empty($filter)) {
			$q->generate_filters($filter, $filtertypes);
		}
		$q->limit($limit, $q->generate_offset($page, $limit));
		$q->order('expiredate', $sortrule);
		$sql = DplusWire::wire('database')->prepare($q->render());
		
		if ($debug) {
			return $q->generate_sqlquery($q->params);
		} else {
			$sql->execute($q->params);
			if ($useclass) {
				$sql->setFetchMode(PDO::FETCH_CLASS, 'Quote');
				return $sql->fetchAll();
			}
			return $sql->fetchAll(PDO::FETCH_ASSOC);
		}
	}
	
	function get_customerquotesorderby($sessionID, $custID, $shipID, $limit = 10, $page = 1, $sortrule, $orderby, $filter = false, $filtertypes = false, $useclass = true, $debug = false) {
		$q = (new QueryBuilder())->table('quothed');
		$q->where('sessionid', $sessionID);
		$q->where('custid', $custID);
		if (!empty($shipID)) {
			$q->where('shiptoid', $shipID);
		}
		if (!empty($filter)) {
			$q->generate_filters($filter, $filtertypes);
		}
		$q->limit($limit, $q->generate_offset($page, $limit));
		$q->order($orderby, $sortrule);
		$sql = DplusWire::wire('database')->prepare($q->render());
		
		if ($debug) {
			return $q->generate_sqlquery($q->params);
		} else {
			$sql->execute($q->params);
			if ($useclass) {
				$sql->setFetchMode(PDO::FETCH_CLASS, 'Quote');
				return $sql->fetchAll();
			}
			return $sql->fetchAll(PDO::FETCH_ASSOC);
		}
	}
	
	function get_custidfromquote($sessionID, $qnbr, $debug = false) {
		$q = (new QueryBuilder())->table('quothed');
		$q->field('custid');
		$q->where('sessionid', $sessionID);
		$q->where('quotnbr', $qnbr);
		$sql = DplusWire::wire('database')->prepare($q->render());
		
		if ($debug) {
			return $q->generate_sqlquery($q->params);
		} else {
			$sql->execute($q->params);
			return $sql->fetchColumn();
		}
	}
	
	function get_shiptoidfromquote($sessionID, $qnbr, $debug = false) {
		$q = (new QueryBuilder())->table('quothed');
		$q->field('shiptoid');
		$q->where('sessionid', $sessionID);
		$q->where('quotnbr', $qnbr);
		$sql = DplusWire::wire('database')->prepare($q->render());
		
		if ($debug) {
			return $q->generate_sqlquery($q->params);
		} else {
			$sql->execute($q->params);
			return $sql->fetchColumn();
		}
	}

	function get_quotehead($sessionID, $qnbr, $useclass = false, $debug = false) {
		$q = (new QueryBuilder())->table('quothed');
		$q->where('sessionid', $sessionID);
		$q->where('quotnbr', $qnbr);
		$sql = Processwire\wire('database')->prepare($q->render());
		
		if ($debug) {
			return $q->generate_sqlquery($q->params);
		} else {
			$sql->execute($q->params);
			if ($useclass) {
				$sql->setFetchMode(PDO::FETCH_CLASS, 'Quote');
				return $sql->fetch();
			}
			return $sql->fetch(PDO::FETCH_ASSOC);
		}
	}

	function get_quotedetails($sessionID, $qnbr, $useclass, $debug) {
		$sql = Processwire\wire('database')->prepare("SELECT * FROM quotdet WHERE sessionid = :sessionID AND quotenbr = :qnbr");
		$switching = array(':sessionID' => $sessionID, ':qnbr' => $qnbr); $withquotes = array(true, true);
		if ($debug) {
			returnsqlquery($sql->queryString, $switching, $withquotes);
		} else {
			$sql->execute($switching);
			if ($useclass) {
				$sql->setFetchMode(PDO::FETCH_CLASS, 'QuoteDetail');
				return $sql->fetchAll();
			}
			return $sql->fetchAll(PDO::FETCH_ASSOC);
		}
	}

	function get_quoteline($sessionID, $qnbr, $line, $debug) {
		$sql = Processwire\wire('database')->prepare("SELECT * FROM quotdet WHERE sessionid = :sessionID AND quotenbr = :qnbr AND linenbr = :line");
		$switching = array(':sessionID' => $sessionID, ':qnbr' => $qnbr, ':line' => $line); $withquotes = array(true, true, true);
		if ($debug) {
			return	returnsqlquery($sql->queryString, $switching, $withquotes);
		} else {
			$sql->execute($switching);
			return $sql->fetch(PDO::FETCH_ASSOC);
		}
	}
	
	function get_quotedetail($sessionID, $qnbr, $linenbr, $debug = false) {
		$q = (new QueryBuilder())->table('quotdet');
		$q->where('sessionid', $sessionID);
		$q->where('quotenbr', $qnbr);
		$q->where('linenbr', $linenbr);
		$sql = Processwire\wire('database')->prepare($q->render());
		
		if ($debug) {
			return $q->generate_sqlquery($q->params);
		} else {
			$sql->execute($q->params);
			$sql->setFetchMode(PDO::FETCH_CLASS, 'QuoteDetail');
			return $sql->fetch();
		}
	}

	function getquotelinedetail($sessionID, $qnbr, $line, $debug) {
		$sql = Processwire\wire('database')->prepare("SELECT * FROM quotdet WHERE sessionid = :sessionID AND quotenbr = :qnbr AND linenbr = :linenbr");
		$switching = array(':sessionID' => $sessionID, ':qnbr' => $qnbr, ':linenbr' => $line); $withquotes = array(true, true, true);
		if ($debug) {
			return returnsqlquery($sql->queryString, $switching, $withquotes);
		} else {
			$sql->execute($switching);
			return $sql->fetch(PDO::FETCH_ASSOC);
		}
	}

	function nextquotelinenbr($sessionID, $qnbr) {
		$sql = Processwire\wire('database')->prepare("SELECT MAX(linenbr) FROM quotdet WHERE sessionid = :sessionID AND quotenbr = :qnbr ");
		$switching = array(':sessionID' => $sessionID, ':qnbr' => $qnbr); $withquotes = array(true, true);
		$sql->execute($switching);
		return intval($sql->fetchColumn()) + 1;
	}
	
	function count_quotedetails($sessionID, $qnbr, $debug = false) {
		$q = (new QueryBuilder())->table('quotdet');
		$q->field($q->expr('COUNT(*)'));
		$q->where('quotenbr', $qnbr);
		$q->where('sessionid', $sessionID);
		$sql = Processwire\wire('database')->prepare($q->render());
		
		if ($debug) {
			return $q->generate_sqlquery();
		} else {
			$sql->execute($q->params);
			return $sql->fetchColumn();
		}
	}

	function edit_quotehead($sessionID, $qnbr, Quote $quote, $debug = false) {
		$originalquote = Quote::load($sessionID, $qnbr);
		$properties = array_keys($quote->_toArray());
		$q = (new QueryBuilder())->table('quothed');
		$q->mode('update');
		
		foreach ($properties as $property) {
			if ($quote->$property != $originalquote->$property) {
				$q->set($property, $quote->$property);
			}
		}
		$q->where('quotnbr', $quote->quotnbr);
		$q->where('sessionid', $quote->sessionid);
		$sql = Processwire\wire('database')->prepare($q->render());
		if ($debug) {
			return $q->generate_sqlquery();
		} else {
			if ($quote->has_changes()) {
				Processwire\wire('session')->execute = true;
				$sql->execute($q->params);
			}
			return $q->generate_sqlquery($q->params);
		}
	}

	function update_quotedetail($sessionID, QuoteDetail $detail, $debug = false) {
		$originaldetail = QuoteDetail::load($sessionID, $detail->quotenbr, $detail->linenbr);
		$properties = array_keys($detail->_toArray());
		$q = (new QueryBuilder())->table('quotdet');
		$q->mode('update');
		foreach ($properties as $property) {
			if ($detail->$property != $originaldetail->$property) {
				$q->set($property, $detail->$property);
			}
		}
		$q->where('quotenbr', $detail->quotenbr);
		$q->where('sessionid', $detail->sessionid);
		$q->where('recno', $detail->recno);
		$sql = Processwire\wire('database')->prepare($q->render());
		
		if ($debug) {
			return $q->generate_sqlquery();
		} else {
			if ($detail->has_changes()) {
				$sql->execute($q->params);
			}
			return $q->generate_sqlquery($q->params);
		}
	}
	
	function insert_quotedetail($sessionID, QuoteDetail $detail, $debug = false) {
		$properties = array_keys($detail->_toArray());
		$q = (new QueryBuilder())->table('quotdet');
		$q->mode('insert');
		foreach ($properties as $property) {
			if (!empty($detail->$property) || strlen($detail->$property)) {
				$q->set($property, $detail->$property);
			}
		}
		$q->where('quotenbr', $detail->quotenbr);
		$q->where('sessionid', $detail->sessionid);
		$q->where('recno', $detail->recno);
		$sql = Processwire\wire('database')->prepare($q->render());
		
		if ($debug) {
			return $q->generate_sqlquery();
		} else {
			if ($detail->has_changes()) {
				$sql->execute($q->params);
			}
			return $q->generate_sqlquery($q->params);
		}
	}

	function insert_orderlock($sessionID, $recnbr, $ordn, $userID, $date, $time, $debug) {
		$sql = Processwire\wire('database')->prepare("INSERT INTO ordlock (sessionid, recno, date, time, orderno, userid) VALUES (:sessionID, :recnbr, :date, :time, :orderno, :userID)");
		$switching = array(':sessionID' => $sessionID, ':recnbr' => $recnbr, ':date' => $time, ':time' => $time, ':orderno' => $ordn, ':userID' => $userID);
		$withquotes = array(true, true, true, true, true, true);
		
		if ($debug) {
			return	returnsqlquery($sql->queryString, $switching, $withquotes);
		} else {
			$sql->execute($switching);
			return returnsqlquery($sql->queryString, $switching, $withquotes);
		}
	}

	function remove_orderlock($sessionID, $ordn, $userID, $debug) {
		$sql = Processwire\wire('database')->prepare("DELETE FROM ordlock WHERE sessionid = :sessionID AND orderno = :ordn AND userid = :userID");
		$switching = array(':sessionID' => $sessionID, ':ordn' => $ordn, ':userID' => $userID);
		$withquotes = array(true, true, true);
		
		if ($debug) {
			return	returnsqlquery($sql->queryString, $switching, $withquotes);
		} else {
			$sql->execute($switching);
			return returnsqlquery($sql->queryString, $switching, $withquotes);
		}
	}

/* =============================================================
	QNOTES FUNCTIONS
============================================================ */
	function get_qnotes($sessionID, $key1, $key2, $type, $useclass = false, $debug = false) {
		$q = (new QueryBuilder())->table('qnote');
		$q->where('sessionid', $sessionID);
		$q->where('key1', $key1);
		$q->where('key2', $key2);
		$q->where('rectype', $type);
		$sql = Processwire\wire('database')->prepare($q->render());
		
		if ($debug) {
			return $q->generate_sqlquery($q->params);
		} else {
			$sql->execute($q->params);
			if ($useclass) {
				$sql->setFetchMode(PDO::FETCH_CLASS, 'QNote');
				return $sql->fetchAll();
			}
			return $sql->fetchAll(PDO::FETCH_ASSOC);
		}
	}
	
	function get_qnote($sessionID, $key1, $key2, $type, $recnbr, $useclass = false, $debug = false) {
		$q = (new QueryBuilder())->table('qnote');
		$q->where('sessionid', $sessionID);
		$q->where('key1', $key1);
		$q->where('key2', $key2);
		$q->where('rectype', $type);
		$q->where('recno', $recnbr);
		$sql = Processwire\wire('database')->prepare($q->render());
		
		if ($debug) {
			return $q->generate_sqlquery($q->params);
		} else {
			$sql->execute($q->params);
			if ($useclass) {
				$sql->setFetchMode(PDO::FETCH_CLASS, 'QNote');
				return $sql->fetch();
			}
			return $sql->fetch(PDO::FETCH_ASSOC);
		}
	}
	
	function count_qnotes($sessionID, $key1, $key2, $type, $debug = false) {
		$q = (new QueryBuilder())->table('qnote');
		$q->field($q->expr('COUNT(*)'));
		$q->where('sessionid', $sessionID);
		$q->where('key1', $key1);
		$q->where('key2', $key2);
		$q->where('rectype', $type);
		$sql = Processwire\wire('database')->prepare($q->render());
		
		if ($debug) {
			return $q->generate_sqlquery($q->params);
		} else {
			$sql->execute($q->params);
			return $sql->fetchColumn();
		}
	}

	function has_dplusnote($sessionID, $key1, $key2, $type) {
		if (count_qnotes($sessionID, $key1, $key2, $type)) {
			return 'Y';
		} else {
			return 'N';
		}
	}
	
	function update_note($sessionID, Qnote $qnote, $debug = false) {
		$originalnote = Qnote::load($sessionID, $qnote->key1, $qnote->key2, $qnote->rectype, $qnote->recno); // LOADS as Class
		$q = (new QueryBuilder())->table('qnote');
		$q->mode('update');
		$q->set('notefld', $qnote->notefld);
		$q->where('sessionid', $sessionID);
		$q->where('key1', $qnote->key1);
		$q->where('key2', $qnote->key2);
		$q->where('form1', $qnote->form1);
		$q->where('form2', $qnote->form2);
		$q->where('form3', $qnote->form3);
		$q->where('form4', $qnote->form4);
		$q->where('form5', $qnote->form5);
		$q->where('recno', $qnote->recno);
		$sql = Processwire\wire('database')->prepare($q->render());
		
		if ($debug) {
			return $q->generate_sqlquery($q->params);
		} else {
			$sql->execute($q->params);
			return array(
				'sql' => $q->generate_sqlquery($q->params), 
				'success' => $sql->rowCount() ? true : false, 
				'updated' => $sql->rowCount() ? true : false, 
				'querytype' => 'update'
			);
		}
	}
	
	function add_qnote($sessionID, Qnote $qnote, $debug = false) {
		$q = (new QueryBuilder())->table('qnote');
		$q->mode('insert');
		$qnote->recno = get_maxqnoterecnbr($qnote->sessionid, $qnote->key1, $qnote->key2, $qnote->rectype) + 1;
		
		foreach ($qnote->_toArray() as $property => $value) {
			$q->set($property, $value);
		}
		$sql = Processwire\wire('database')->prepare($q->render());
		
		if ($debug) {
			return $q->generate_sqlquery($q->params);
		} else {
			$sql->execute($q->params);
			return array(
				'sql' => $q->generate_sqlquery($q->params), 
				'success' => $sql->rowCount() ? true : false, 
				'updated' => $sql->rowCount() ? true : false, 
				'querytype' => 'insert'
			);
		}
	}
	
	function get_maxqnoterecnbr($sessionID, $key1, $key2, $rectype, $debug = false) {
		$q = (new QueryBuilder())->table('qnote');
		$q->field($q->expr('MAX(recno)'));
		$q->where('sessionid', $sessionID);
		$q->where('key1', $key1);
		$q->where('key2', $key2);
		//$q->where('rectype', $rectype);
		$sql = Processwire\wire('database')->prepare($q->render());
		
		if ($debug) {
			return $q->generate_sqlquery($q->params);
		} else {
			$sql->execute($q->params);
			return intval($sql->fetchColumn());
		}
	}
	
	function delete_note($sessionID, Qnote $qnote, $debug = false) {
		$q = (new QueryBuilder())->table('qnote');
		$q->mode('delete');
		$q->where('sessionid', $sessionID);
		$q->where('key1', $qnote->key1);
		$q->where('key2', $qnote->key2);
		$q->where('form1', $qnote->form1);
		$q->where('form2', $qnote->form2);
		$q->where('form3', $qnote->form3);
		$q->where('form4', $qnote->form4);
		$q->where('form5', $qnote->form5);
		$q->where('recno', $qnote->recno);
		$q->where('rectype', $qnote->rectype);
		$sql = Processwire\wire('database')->prepare($q->render());
		
		if ($debug) {
			return $q->generate_sqlquery($q->params);
		} else {
			$sql->execute($q->params);
			return array(
				'sql' => $q->generate_sqlquery($q->params), 
				'success' => $sql->rowCount() ? true : false, 
				'updated' => $sql->rowCount() ? true : false, 
				'querytype' => 'update'
			);
		}
	}

/* =============================================================
	PRODUCT FUNCTIONS
============================================================ */
	function get_itemsearchresults($sessionID, $limit = 10, $page = 1, $debug = false) {
		$q = (new QueryBuilder())->table('pricing');
		$q->where('sessionid', $sessionID);
		$q->limit($limit, $q->generate_offset($page, $limit));
		$sql = Processwire\wire('database')->prepare($q->render());
		
		if ($debug) {
			return $q->generate_sqlquery($q->params);
		} else {
			$sql->execute($q->params);
			$sql->setFetchMode(PDO::FETCH_CLASS, 'PricingItem');
			return $sql->fetchAll();
		}
	}
	
	function count_itemsearchresults($sessionID, $debug = false) {
		$q = (new QueryBuilder())->table('pricing');
		$q->field($q->expr('COUNT(*)'));
		$q->where('sessionid', $sessionID);
		$sql = Processwire\wire('database')->prepare($q->render());
		
		if ($debug) {
			return $q->generate_sqlquery($q->params);
		} else {
			$sql->execute($q->params);
			return $sql->fetchColumn();
		}
	}
	
	function count_itemhistory($sessionID, $itemID, $debug = false) {
		$q = (new QueryBuilder())->table('custpricehistory');
		$q->field($q->expr('COUNT(*)'));
		$q->where('sessionid', $sessionID);
		$q->where('itemid', $itemID);
		$sql = Processwire\wire('database')->prepare($q->render());
		
		if ($debug) {
			return $q->generate_sqlquery($q->params);
		} else {
			$sql->execute($q->params);
			return $sql->fetchColumn();
		}
	}
	
	function get_itemhistoryfield($sessionID, $itemID, $field, $debug = false) {
		$q = (new QueryBuilder())->table('custpricehistory');
		$q->field($field);
		$q->where('sessionid', $sessionID);
		$q->where('itemid', $itemID);
		$sql = Processwire\wire('database')->prepare($q->render());
		
		if ($debug) {
			return $q->generate_sqlquery($q->params);
		} else {
			$sql->execute($q->params);
			return $sql->fetchColumn();
		}	
	}
	
	function get_itemavailability($sessionID, $itemID, $debug = false) {
		$q = (new QueryBuilder())->table('whseavail');
		$q->where('sessionid', $sessionID);
		$q->where('itemid', $itemID);
		$sql = Processwire\wire('database')->prepare($q->render());
		
		if ($debug) {
			return $q->generate_sqlquery($q->params);
		} else {
			$sql->execute($q->params);
			return $sql->fetchAll(PDO::FETCH_ASSOC);
		}
	}
	
	function get_commissionprices($itemID, $debug = false) {
		$q = (new QueryBuilder())->table('commprice');
		$q->where('itemid', $itemID);
		$q->order('percent DESC');
		$sql = Processwire\wire('database')->prepare($q->render());
		
		if ($debug) {
			return $q->generate_sqlquery($q->params);
		} else {
			$sql->execute($q->params);
			return $sql->fetchAll(PDO::FETCH_ASSOC);
		}
	}
	
	function get_pricingitem($sessionID, $itemID, $debug = false) {
		$q = (new QueryBuilder())->table('pricing');
		$q->where('sessionid', $sessionID);
		$q->where('itemid', $itemID);
		$sql = Processwire\wire('database')->prepare($q->render());
		
		if ($debug) {
			return $q->generate_sqlquery($q->params);
		} else {
			$sql->execute($q->params);
			$sql->setFetchMode(PDO::FETCH_CLASS, 'PricingItem');
			return $sql->fetch();
		}
	}

	/* =============================================================
		USER ACTION FUNCTIONS
	============================================================ */
	function get_useractions($user, $querylinks, $limit, $page, $debug) {
		$q = (new QueryBuilder())->table('useractions');
		
		if (Processwire\wire('config')->cptechcustomer == 'stempf') {
			$q->generate_query($querylinks, "duedate-ASC", $limit, $page);
		} else {
			$q->generate_query($querylinks, false, $limit, $page);
		}
		
		$sql = Processwire\wire('database')->prepare($q->render());
		
		if ($debug) {
			return $q->generate_sqlquery($q->params);
		} else {
			$sql->execute($q->params);
			$sql->setFetchMode(PDO::FETCH_CLASS, 'UserAction');
			return $sql->fetchAll();
		}
	}

	function count_useractions($user, $querylinks, $debug) {
		$q = (new QueryBuilder())->table('useractions');
		$q->field($q->expr('COUNT(*)'));
		$q->generate_query($querylinks, false, false, false);
		$sql = Processwire\wire('database')->prepare($q->render());
		
		if ($debug) {
			return $q->generate_sqlquery($q->params);
		} else {
			$sql->execute($q->params);
			return $sql->fetchColumn();
		}
	}

	function get_useraction($id, $debug = false) {
		$q = (new QueryBuilder())->table('useractions');
		$q->where('id', $id);
		$sql = Processwire\wire('database')->prepare($q->render());
		
		if ($debug) {
			return $q->generate_sqlquery($q->params);
		} else {
			$sql->execute($q->params);
			$sql->setFetchMode(PDO::FETCH_CLASS, 'UserAction');
			return $sql->fetch();
		}
	}
	
	function edit_useraction(UserAction $updatedaction, $debug = false) {
		$originalaction = get_useraction($updatedaction->id); // (id, bool fetchclass, bool debug)
		$q = (new QueryBuilder())->table('useractions');
		$q->mode('update');
		$q->generate_setdifferencesquery($originalaction->_toArray(), $updatedaction->_toArray());
		$q->where('id', $updatedaction->id);
		$sql = Processwire\wire('database')->prepare($q->render());
		
		if ($debug) {
			return $q->generate_sqlquery();
		} else {
			$sql->execute($q->params);
			$success = $sql->rowCount();
			if ($success) {
				return array("error" => false,  "sql" => $q->generate_sqlquery($q->params));
			} else {
				return array("error" => true,  "sql" => $q->generate_sqlquery($q->params));
			}
		}
	}
	
	function update_useraction(UserAction $updatedaction, $debug = false) {
		return edit_useraction($updatedaction, $debug);
	}

	function update_useractionlinks($oldlinks, $newlinks, $wherelinks, $debug) {
		$q = (new QueryBuilder())->table('useractions');
		$q->mode('update');
		$q->generate_setdifferencesquery($oldlinks, $newlinks);
		$q->generate_query($wherelinks);
		$q->set('dateupdated', date("Y-m-d H:i:s"));
		$sql = Processwire\wire('database')->prepare($q->render());
		
		if ($debug) {
			return $q->generate_sqlquery();
		} else {
			$sql->execute($q->params);
			$success = $sql->rowCount();
			if ($success) {
				return array("error" => false,  "sql" => $q->generate_sqlquery($q->params));
			} else {
				return array("error" => true,  "sql" => $q->generate_sqlquery($q->params));
			}
		}
	}
	
	function create_useraction(UserAction $action, $debug = false) {
		$q = (new QueryBuilder())->table('useractions');
		$q->mode('insert');
		$q->generate_setvaluesquery($action->_toArray());
		$sql = Processwire\wire('database')->prepare($q->render());
		
		if ($debug) {
			return $q->generate_sqlquery($q->params);
		} else {
			$sql->execute($q->params);
			return array('sql' => $q->generate_sqlquery($q->params), 'insertedid' => Processwire\wire('database')->lastInsertId());
		}
	}

	function get_useractions_maxrec($loginID) {
		$sql = Processwire\wire('database')->prepare("SELECT MAX(id) AS id FROM useractions WHERE createdby = :login");
		$switching = array(':login' => $loginID);
		$withquotes = array(true, true);
		$sql->execute($switching);
		return $sql->fetchColumn();
	}
/* =============================================================
	VENDOR FUNCTIONS
============================================================ */
	function getvendors($debug) {
		$sql = Processwire\wire('database')->prepare("SELECT * FROM vendors WHERE shipfrom = ''");
		$switching = array(); $withquotes = array();
		if ($debug) {
			return returnsqlquery($sql->queryString, $switching, $withquotes);
		} else {
			$sql->execute();
			return $sql->fetchAll(PDO::FETCH_ASSOC);
		}
	}
	
	function get_vendor($vendorID, $shipfromID = '', $debug = false) {
		$q = (new QueryBuilder())->table('vendors');
		$q->where('vendid', $vendorID);
		$q->where('shipfrom', $shipfromID);
		$sql = Processwire\wire('database')->prepare($q->render());
		
		if ($debug) {
			return $q->generate_sqlquery($q->params);
		} else {
			$sql->execute($q->params);
			$sql->setFetchMode(PDO::FETCH_CLASS, 'Vendor');
			return $sql->fetch();
		}
	}

	function getvendorshipfroms($vendorID, $debug) {
		$sql = Processwire\wire('database')->prepare("SELECT * FROM vendors WHERE vendid = :vendor AND shipfrom != ''");
		$switching = array(':vendor' => $vendorID); $withquotes = array(true);
		if ($debug) {
			return returnsqlquery($sql->queryString, $switching, $withquotes);
		} else {
			$sql->execute($switching);
			return $sql->fetchAll(PDO::FETCH_ASSOC);
		}
	}
	
	function search_vendorspaged($limit = 10, $page = 1, $keyword, $debug) {
		$SHARED_ACCOUNTS = Processwire\wire('config')->sharedaccounts;
		$limiting = returnlimitstatement($limit, $page);
		$search = '%'.str_replace(' ', '%',$keyword).'%';
		$sql = Processwire\wire('database')->prepare("SELECT * FROM vendors WHERE UCASE(CONCAT(vendid, ' ', shipfrom, ' ', name, ' ', address1, ' ', address2, ' ', address3, ' ', city, ' ', state, ' ', zip, ' ', country, ' ', phone, ' ', fax, ' ', email)) LIKE UCASE(:search) $limiting");
		$switching = array(':search' => $search); $withquotes = array(true);
		if ($debug) {
			return returnsqlquery($sql->queryString, $switching, $withquotes);
		} else {
			$sql->execute($switching);
			return $sql->fetchAll(PDO::FETCH_ASSOC);
		}
	}
	
	function count_searchvendors($keyword, $debug) {
		$SHARED_ACCOUNTS = Processwire\wire('config')->sharedaccounts;
		$search = '%'.str_replace(' ', '%',$keyword).'%';
		$sql = Processwire\wire('database')->prepare("SELECT COUNT(*) FROM vendors WHERE UCASE(CONCAT(vendid, ' ', shipfrom, ' ', name, ' ', address1, ' ', address2, ' ', address3, ' ', city, ' ', state, ' ', zip, ' ', country, ' ', phone, ' ', fax, ' ', email)) LIKE UCASE(:search)");
		$switching = array(':search' => $search); $withquotes = array(true);
		if ($debug) {
			return returnsqlquery($sql->queryString, $switching, $withquotes);
		} else {
			$sql->execute($switching);
			return $sql->fetchAll(PDO::FETCH_ASSOC);
		}
	}

	function getunitofmeasurements($debug) {
		$sql = Processwire\wire('database')->prepare("SELECT * FROM unitofmeasure");
		if ($debug) {
			return $sql->queryString;
		} else {
			$sql->execute();
			return $sql->fetchAll(PDO::FETCH_ASSOC);
		}
	}

	function getitemgroups($debug) {
		$sql = Processwire\wire('database')->prepare("SELECT * FROM itemgroup");
		if ($debug) {
			return $sql->queryString;
		} else {
			$sql->execute();
			return $sql->fetchAll(PDO::FETCH_ASSOC);
		}
	}
	
	function get_vendorname($vendorID) {
		$sql = Processwire\wire('database')->prepare("SELECT name FROM vendors WHERE vendid = :vendorID LIMIT 1");
		$switching = array(':vendorID' => $vendorID);
		$sql->execute($switching);
		return $sql->fetchColumn();
	}

/* =============================================================
	CART FUNCTIONS
============================================================ */
	function count_carthead($sessionID, $debug = false) {
		$q = (new QueryBuilder())->table('carthed');
		$q->field("COUNT(*)");
		$q->where('sessionid', $sessionID);
		$sql = Processwire\wire('database')->prepare($q->render());
		
		if ($debug) {
			return $q->generate_sqlquery($q->params);
		} else {
			$sql->execute($q->params);
			return $sql->fetchColumn();
		}
	}
	
	function get_custidfromcart($sessionID, $debug = false) {
		$q = (new QueryBuilder())->table('carthed');
		$q->field($q->expr('custid'));
		$q->where('sessionid', $sessionID);
		$sql = Processwire\wire('database')->prepare($q->render());
		
		if ($debug) {
			return $q->generate_sqlquery($q->params);
		} else {
			$sql->execute($q->params);
			return $sql->fetchColumn();
		}
	}
	
	function get_carthead($sessionID, $useclass = false, $debug = false) {
		$q = (new QueryBuilder())->table('carthed');
		$q->where('sessionid', $sessionID);
		$sql = Processwire\wire('database')->prepare($q->render());
		
		if ($debug) {
			return $q->generate_sqlquery($q->params);
		} else {
			$sql->execute($q->params);
			if ($useclass) {
				$sql->setFetchMode(PDO::FETCH_CLASS, 'CartQuote'); // CAN BE SalesOrder|SalesOrderEdit
				return $sql->fetch();
			}
			return $sql->fetch(PDO::FETCH_ASSOC);
		}
	}

	function editcarthead($sessionID, $carthead, $debug) {
		$orginalcarthead = getcarthead($sessionID, false);
		$query = returnpreppedquery($originalcarthead, $carthead);
		$sql = Processwire\wire('database')->prepare("UPDATE carthed SET ".$query['setstatement']." WHERE sessionid = :sessionID");
		$query['switching'][':sessionID'] = $sessionID; $query['withquotes'][] = true;
		if ($debug) {
			return returnsqlquery($sql->queryString, $query['switching'], $query['withquotes']);
		} else {
			if ($query['changecount'] > 0) {
				$sql->execute($query['switching']);
			}
			return returnsqlquery($sql->queryString, $query['switching'], $query['withquotes']);
		}
	}
	
	function count_cartdetails($sessionID, $debug = false) {
		$q = (new QueryBuilder())->table('cartdet');
		$q->field('COUNT(*)');
		$q->where('sessionid', $sessionID);
		$sql = Processwire\wire('database')->prepare($q->render());
		
		if ($debug) {
			return $q->generate_sqlquery($q->params);
		} else {
			$sql->execute($q->params);
			return $sql->fetchColumn();
		}
	}
	
	function get_cartdetails($sessionID, $useclass = false, $debug = false) {
		$q = (new QueryBuilder())->table('cartdet');
		$q->where('sessionid', $sessionID);
		$sql = Processwire\wire('database')->prepare($q->render());
		
		if ($debug) {
			return $q->generate_sqlquery($q->params);
		} else {
			$sql->execute($q->params);
			if ($useclass) {
				$sql->setFetchMode(PDO::FETCH_CLASS, 'CartDetail'); // CAN BE SalesOrder|SalesOrderEdit
				return $sql->fetchAll();
			}
			return $sql->fetchAll(PDO::FETCH_ASSOC);
		}
	}
	
	function get_cartdetail($sessionID, $linenbr, $debug = false) {
		$q = (new QueryBuilder())->table('cartdet');
		$q->where('sessionid', $sessionID);
		$q->where('linenbr', $linenbr);
		$sql = Processwire\wire('database')->prepare($q->render());
		
		if ($debug) {
			return $q->generate_sqlquery($q->params);
		} else {
			$sql->execute($q->params);
			$sql->setFetchMode(PDO::FETCH_CLASS, 'CartDetail');
			return $sql->fetch();
		}
	}

	function insert_carthead($sessionID, $custID, $shipID, $debug) {
		$q = (new QueryBuilder())->table('carthed');
		$q->mode('insert');
		$q->set('sessionid', $sessionID);
		$q->set('custid', $custID);
		$q->set('shiptoid', $shipID);
		$q->set('date', date('Ymd'));
		$q->set('time', date('His'));
		$sql = Processwire\wire('database')->prepare($q->render());
		
		if ($debug) {
			return $q->generate_sqlquery($q->params);
		} else {
			$sql->execute($q->params);
			return $q->generate_sqlquery($q->params);
		}
	}

	function getcartline($sessionID, $linenbr, $debug) {
		$sql = Processwire\wire('database')->prepare("SELECT * FROM cartdet WHERE sessionid = :sessionID AND linenbr = :linenbr");
		$switching = array(':sessionID' => $sessionID, ':linenbr' => $linenbr); $withquotes = array(true, true);
		$sql->execute($switching);
		if ($debug) {
			return returnsqlquery($sql->queryString, $switching, $withquotes);
		} else {
			return $sql->fetch(PDO::FETCH_ASSOC);
		}
	}

	function insertcartline($sessionID, $linenbr, $debug) { // DEPRECATED 3/6/2018
		$sql = Processwire\wire('database')->prepare("INSERT INTO cartdet (sessionid, linenbr) VALUES (:sessionID, :linenbr)");
		$switching = array(':sessionID' => $sessionID, ':linenbr' => $linenbr); $withquotes = array(true, true);
		if ($debug) {
			return returnsqlquery($sql->queryString, $switching, $withquotes);
		} else {
			$sql->execute($switching);
			return array('sql' => returnsqlquery($sql->queryString, $switching, $withquotes), 'insertedid' => Processwire\wire('database')->lastInsertId());
		}
	}

	function getcartlinedetail($sessionID, $linenbr, $debug) {
		return getcartline($sessionID, $linenbr, $debug);
	}

	function edit_cartline($sessionID, $newdetails, $debug) {
		$originaldetail = getcartlinedetail($sessionID, $newdetails['linenbr'], false);
		$query = returnpreppedquery($originaldetail, $newdetails);
		$sql = Processwire\wire('database')->prepare("UPDATE cartdet SET ".$query['setstatement']." WHERE sessionid = :sessionID AND linenbr = :linenbr");
		$query['switching'][':sessionID'] = $sessionID; $query['switching'][':linenbr'] = $newdetails['linenbr'];
		$query['withquotes'][] = true; $query['withquotes'][]= true; $query['withquotes'][] = true;
		if ($debug) {
			return returnsqlquery($sql->queryString, $query['switching'], $query['withquotes']);
		} else {
			if ($query['changecount'] > 0) {
				$sql->execute($query['switching']);
			}
			return returnsqlquery($sql->queryString, $query['switching'], $query['withquotes']);
		}
	}
	
	function update_cartdetail($sessionID, CartDetail $detail, $debug = false) {
		$originaldetail = CartDetail::load($sessionID, $detail->linenbr);
		$properties = array_keys($detail->_toArray());
		$q = (new QueryBuilder())->table('cartdet');
		$q->mode('update');
		foreach ($properties as $property) {
			if ($detail->$property != $originaldetail->$property) {
				$q->set($property, $detail->$property);
			}
		}
		$q->where('sessionid', $detail->sessionid);
	//	$q->where('orderno', $detail->orderno);
		$q->where('linenbr', $detail->linenbr);
		$sql = Processwire\wire('database')->prepare($q->render());
		
		if ($debug) {
			return $q->generate_sqlquery();
		} else {
			if ($detail->has_changes()) {
				$sql->execute($q->params);
			}
			return $q->generate_sqlquery($q->params);
		}
	}
	
	function insert_cartdetail($sessionID, CartDetail $detail, $debug = false) {
		$properties = array_keys($detail->_toArray());
		$q = (new QueryBuilder())->table('cartdet');
		$q->mode('insert');
		foreach ($properties as $property) {
			if (strlen($detail->$property)) {
				$q->set($property, $detail->$property);
			}
		}
		$sql = Processwire\wire('database')->prepare($q->render());
		
		if ($debug) {
			return $q->generate_sqlquery();
		} else {
			$sql->execute($q->params);
			return $q->generate_sqlquery($q->params);
		}
	}

	function nextcartlinenbr($sessionID) {
		$sql = Processwire\wire('database')->prepare("SELECT MAX(linenbr) FROM cartdet WHERE sessionid = :sessionID");
		$switching = array(':sessionID' => $sessionID); $withquotes = array(true);
		$sql->execute($switching);
		return intval($sql->fetchColumn()) + 1;
	}

	function getcreatedordn($sessionID, $debug) { // DEPRECATED 3/6/2018
		$sql = Processwire\wire('database')->prepare("SELECT ordernbr FROM logperm WHERE sessionid = :sessionID");
		$switching = array(':sessionID' => $sessionID); $withquotes = array(true);
		$sql->execute($switching);
		if ($debug) {
			return returnsqlquery($sql->queryString, $switching, $withquotes);
		} else {
			return $sql->fetchColumn();
		}
	}
	
	function get_createdordn($sessionID, $debug = false) {
		$q = (new QueryBuilder())->table('logperm');
		$q->field('ordernbr');
		$q->where('sessionid', $sessionID);
		$sql = Processwire\wire('database')->prepare($q->render());
		
		if ($debug) {
			return $q->generate_sqlquery();
		} else {
			$sql->execute($q->params);
			return $sql->fetchColumn();
		}
	}

/* =============================================================
	EDIT ORDER FUNCTIONS
============================================================ */
	function can_editorder($sessionID, $ordn, $debug) {
		$sql = Processwire\wire('database')->prepare("SELECT editord FROM ordrhed WHERE sessionid = :sessionID AND orderno = :ordn LIMIT 1");
		$switching = array(':sessionID' => $sessionID, ':ordn' => $ordn); $withquotes = array(true, true);
		if ($debug) {
			return returnsqlquery($sql->queryString, $switching, $withquotes);
		} else {
			$sql->execute($switching);
			$column = $sql->fetchColumn();
			if ($column != 'Y') { return false; } else { return true; }
		}
	}
	
	function get_orderhead($sessionID, $ordn, $useclass = false, $debug = false) {
		$q = (new QueryBuilder())->table('ordrhed');
		$q->where('sessionid', $sessionID);
		$q->where('orderno', $ordn);
		$sql = Processwire\wire('database')->prepare($q->render());
		
		if ($debug) {
			return $q->generate_sqlquery($q->params);
		} else {
			$sql->execute($q->params);
			if ($useclass) {
				$sql->setFetchMode(PDO::FETCH_CLASS, 'SalesOrder'); // CAN BE SalesOrder|SalesOrderEdit
				return $sql->fetch();
			}
			return $sql->fetch(PDO::FETCH_ASSOC);
		}
	}

	function getorderdetails($sessionID, $ordn, $debug) {
		$sql = Processwire\wire('database')->prepare("SELECT * FROM ordrdet WHERE sessionid = :sessionID AND orderno = :ordn");
		$switching = array(':sessionID' => $sessionID, ':ordn' => $ordn); $withquotes = array(true, true);
		$sql->execute($switching);
		if ($debug) {
			return returnsqlquery($sql->queryString, $switching, $withquotes);
		} else {
			return $sql->fetchAll(PDO::FETCH_ASSOC);
		}
	}

	function getorderlinedetail($sessionID, $ordn, $linenumber, $debug) {
		$sql = Processwire\wire('database')->prepare("SELECT * FROM ordrdet WHERE sessionid = :sessionID AND orderno = :ordn AND linenbr = :linenbr");
		$switching = array(':sessionID' => $sessionID, ':ordn' => $ordn, ':linenbr' => $linenumber); $withquotes = array(true, true, true);
		if ($debug) {
			return returnsqlquery($sql->queryString, $switching, $withquotes);
		} else {
			$sql->execute($switching);
			return $sql->fetch(PDO::FETCH_ASSOC);
		}
	}
	
	function get_orderdetail($sessionID, $ordn, $linenbr, $debug = false) {
		$q = (new QueryBuilder())->table('ordrdet');
		$q->where('sessionid', $sessionID);
		$q->where('orderno', $ordn);
		$q->where('linenbr', $linenbr);
		$sql = Processwire\wire('database')->prepare($q->render());
		
		if ($debug) {
			return $q->generate_sqlquery($q->params);
		} else {
			$sql->execute($q->params);
			$sql->setFetchMode(PDO::FETCH_CLASS, 'SalesOrderDetail');
			return $sql->fetch();
		}
	}

	function get_allorderdocs($sessionID, $ordn, $debug = false) {
		$q = (new QueryBuilder())->table('orddocs');
		$q->where('sessionid', $sessionID);
		$q->where('orderno', $ordn);
		$sql = Processwire\wire('database')->prepare($q->render());
		
		if ($debug) {
			return $q->generate_sqlquery($q->params);
		} else {
			$sql->execute($q->params);
			return $sql->fetchAll(PDO::FETCH_ASSOC);
		}
	}

	function get_ordertracking($sessionID, $ordn, $debug = false) {
		$q = (new QueryBuilder())->table('ordrtrk');
		$q->where('sessionid', $sessionID);
		$q->where('orderno', $ordn);
		$sql = Processwire\wire('database')->prepare($q->render());
		
		if ($debug) {
			return $q->generate_sqlquery($q->params);
		} else {
			$sql->execute($q->params);
			return $sql->fetchAll(PDO::FETCH_ASSOC);
		}
	}
	
	function update_orderdetail($sessionID, $detail, $debug = false) {
		$originaldetail = SalesOrderDetail::load($sessionID, $detail->orderno, $detail->linenbr);
		$properties = array_keys($detail->_toArray());
		$q = (new QueryBuilder())->table('ordrdet');
		$q->mode('update');
		foreach ($properties as $property) {
			if ($detail != $originaldetail->$property) {
				$q->set($property, $detail->$property);
			}
		}
		$q->where('orderno', $detail->orderno);
		$q->where('sessionid', $detail->sessionid);
		$q->where('linenbr', $detail->linenbr);
		$sql = Processwire\wire('database')->prepare($q->render());
		
		if ($debug) {
			return $q->generate_sqlquery();
		} else {
			if ($detail->has_changes()) {
				$sql->execute($q->params);
			}
			return $q->generate_sqlquery($q->params);
		}
	}

	function edit_orderhead($sessionID, $ordn, $order, $debug = false) {
		$orginalorder = SalesOrder::load($sessionID, $ordn);
		$properties = array_keys($order->_toArray());
		$q = (new QueryBuilder())->table('ordrhed');
		$q->mode('update');
		foreach ($properties as $property) {
			if ($order->$property != $orginalorder->$property) {
				$q->set($property, $order->$property);
			}
		}
		$q->where('orderno', $order->orderno);
		$q->where('sessionid', $order->sessionid);
		$sql = Processwire\wire('database')->prepare($q->render());
		
		if ($debug) {
			return $q->generate_sqlquery();
		} else {
			if ($order->has_changes()) {
				$sql->execute($q->params);
			}
			return $q->generate_sqlquery($q->params);
		}
	}

	function edit_orderhead_credit($sessionID, $ordn, $paytype, $ccno, $expdate, $ccv, $debug = false) {
		$q = (new QueryBuilder())->table('ordrhed');
		$q->mode('update');
		$q->set('paymenttype', $paytype);
		$q->set('cardnumber', $q->expr('AES_ENCRYPT([], HEX([]))', [$ccno, $sessionID]));
		$q->set('cardexpire', $q->expr('AES_ENCRYPT([], HEX([]))', [$expdate, $sessionID]));
		$q->set('cardcode', $q->expr('AES_ENCRYPT([], HEX([]))', [$ccv, $sessionID]));
		$q->where('orderno', $ordn);
		$q->where('sessionid', $sessionID);
		$sql = Processwire\wire('database')->prepare($q->render());
		
		if ($debug) {
			return $q->generate_sqlquery();
		} else {
			$sql->execute($q->params);
			return $q->generate_sqlquery($q->params);
		}
	}

	function get_ordercreditcard($sessionID, $ordn, $debug) {
		$sql = Processwire\wire('database')->prepare("SELECT sessionid, AES_DECRYPT(cardnumber, HEX(sessionid)) AS cardnumber, AES_DECRYPT(cardnumber , HEX(sessionid)) AS cardcode, AES_DECRYPT(cardexpire, HEX(sessionid)) AS expiredate FROM ordrhed WHERE sessionid = :sessionID AND orderno = :ordn AND type = 'O'");
		$switching = array(':sessionID' => $sessionID, ':ordn' => $ordn); $withquotes = array(true, true);
		$sql->execute($switching);
		if ($debug) {
			return returnsqlquery($sql->queryString, $switching, $withquotes);
		} else {
			$sql->setFetchMode(PDO::FETCH_CLASS, 'OrderCreditCard');
			return $sql->fetch();
		}
	}

	function getshipvias($sessionID) {
		$sql = Processwire\wire('database')->prepare("SELECT code, via FROM shipvia WHERE sessionid = :sessionID");
		$switching = array(':sessionID' => $sessionID); $withquotes = array(true);
		$sql->execute($switching);
		return $sql->fetchAll(PDO::FETCH_ASSOC);
	}

/* =============================================================
	MISC ORDER FUNCTIONS
============================================================ */
	function getstates() {
		$sql = Processwire\wire('database')->prepare("SELECT abbreviation as state, name FROM states");
		$sql->execute();
		return $sql->fetchAll(PDO::FETCH_ASSOC);
	}

	function getcountries() {
		$sql = Processwire\wire('database')->prepare("SELECT * FROM countries");
		$sql->execute();
		return $sql->fetchAll(PDO::FETCH_ASSOC);
	}



/* =============================================================
	ITEM FUNCTIONS
============================================================ */
	function getiteminfo($sessionID, $itemID, $debug) {
		$sql = Processwire\wire('database')->prepare("SELECT * FROM pricing WHERE sessionid = :sessionID AND itemid = :itemid LIMIT 1");
		$switching = array(':sessionID' => $sessionID, ':itemid' => $itemID); $withquotes = array(true, true);
		if ($debug) {
			return returnsqlquery($sql->queryString, $switching, $withquotes);
		} else {
			$sql->execute($switching);
			return $sql->fetch(PDO::FETCH_ASSOC);
		}
	}

	function getitemfromim($itemID, $debug) {
		$sql = Processwire\wire('database')->prepare("SELECT * FROM pricing WHERE itemid = :itemid LIMIT 1");
		$switching = array(':itemid' => $itemID); $withquotes = array(true);
		if ($debug) {
			return returnsqlquery($sql->queryString, $switching, $withquotes);
		} else {
			$sql->execute($switching);
			return $sql->fetch(PDO::FETCH_ASSOC);
		}
	}

	/* =============================================================
		ITEM MASTER FUNCTIONS
	============================================================ */
	function search_items($query, $custID, $limit, $page, $debug = false) {
		$search = '%'.str_replace(' ', '%', $query).'%';
		$q = (new QueryBuilder())->table('itemsearch');
		
		if (empty($custID)) {
			$q->where('origintype', ['I', 'V']);
			$q->where(
		        $q
		        ->orExpr()
		        ->where($q->expr("UCASE(CONCAT(itemid, ' ', originid, ' ', desc1, ' ', desc2))"), 'like', $q->expr("UCASE([])",[$search]))
		        ->where($q->expr("UCASE(CONCAT(itemid, ' ', refitemid, ' ', desc1, ' ', desc2))"), 'like', $q->expr("UCASE([])",[$search]))
		    );
		} else {
			$q->where('origintype', ['I', 'V', 'C']);
			$q->where($q->expr("UCASE(CONCAT(itemid, ' ', refitemid, ' ', desc1, ' ', desc2))"), 'like', $q->expr("UCASE([])",[$search]));
		}
		$q->order($q->expr("itemid LIKE UCASE([]) DESC", [$search]));
		$q->group('itemid');
		$q->limit($limit, $q->generate_offset($page, $limit));
		
		$sql = Processwire\wire('database')->prepare($q->render());
		
		if ($debug) {
			return $q->generate_sqlquery($q->params);
		} else {
			$sql->execute($q->params);
			$sql->setFetchMode(PDO::FETCH_CLASS, 'XRefItem');
			return $sql->fetchAll();
		}
	}
	
	function count_searchitems($q, $custID, $debug = false) {
		$search = '%'.str_replace(' ', '%', $q).'%';
		$q = (new QueryBuilder())->table('itemsearch');
		$q->field('COUNT(DISTINCT(itemid))');
		
		if (empty($custID)) {
			$q->where('origintype', ['I', 'V']);
			$q->where(
		        $q
		        ->orExpr()
		        ->where($q->expr("UCASE(CONCAT(itemid, ' ', originid, ' ', desc1, ' ', desc2))"), 'like', $q->expr("UCASE([])",[$search]))
		        ->where($q->expr("UCASE(CONCAT(itemid, ' ', refitemid, ' ', desc1, ' ', desc2))"), 'like', $q->expr("UCASE([])",[$search]))
		    );
		} else {
			$q->where('origintype', ['I', 'V', 'C']);
			$q->where($q->expr("UCASE(CONCAT(itemid, ' ', refitemid, ' ', desc1, ' ', desc2))"), 'like', $q->expr("UCASE([])",[$search]));
		}
		$sql = Processwire\wire('database')->prepare($q->render());
		
		if ($debug) {
			return $q->generate_sqlquery($q->params);
		} else {
			$sql->execute($q->params);
			return $sql->fetchColumn();
		}
	}

	function validateitemid($itemID, $custID, $debug) {
		if (empty($custID)) {
			$sql = Processwire\wire('database')->prepare("SELECT COUNT(*) FROM itemsearch WHERE UCASE(itemid) = UCASE(:itemID) AND originid = 'I'");
			$switching = array(':itemID' => $itemID); $withquotes = array(true);
		} else {
			$sql = Processwire\wire('database')->prepare("SELECT COUNT(*) FROM itemsearch WHERE (originid = (:custID) AND UCASE(refitemid) = UCASE(:itemID)) OR (UCASE(itemid) = UCASE(:itemID) AND origintype = 'I')");
			$switching = array(':itemID' => $itemID, ':custID' => $custID); $withquotes = array(true, true);
		}

		if ($debug) {
			return returnsqlquery($sql->queryString, $switching, $withquotes);
		} else {
			$sql->execute($switching);
			return $sql->fetchColumn();
		}
	}

	function getitemdescription($itemID, $debug) {
		$sql = Processwire\wire('database')->prepare("SELECT desc1 FROM itemsearch WHERE itemid = :itemid LIMIT 1");
		$switching = array(':itemid' => $itemID); $withquotes = array(true);
		if ($debug) {
			return returnsqlquery($sql->queryString, $switching, $withquotes);
		} else {
			$sql->execute($switching);
			return $sql->fetchColumn();
		}
	}

	function getnextrecno($itemID, $nextorprev, $debug) {
		if ($nextorprev == 'next') {
			$sql = Processwire\wire('database')->prepare("SELECT MAX(recno) + 1 FROM itemsearch WHERE itemid = :itemid");
		} else {
			$sql = Processwire\wire('database')->prepare("SELECT MIN(recno) - 1 FROM itemsearch WHERE itemid = :itemid");
		}
		$switching = array(':itemid' => $itemID); $withquotes = array(true);
		if ($debug) {
			return returnsqlquery($sql->queryString, $switching, $withquotes);
		} else {
			$sql->execute($switching);
			return $sql->fetchColumn();
		}
	}

	function getitembyrecno($recno, $debug) {
		$sql = Processwire\wire('database')->prepare("SELECT itemid FROM itemsearch WHERE recno = :recno");
		$switching = array(':recno' => $recno); $withquotes = array(true);
		if ($debug) {
			return returnsqlquery($sql->queryString, $switching, $withquotes);
		} else {
			$sql->execute($switching);
			return $sql->fetchColumn();
		}

	}

	/* =============================================================
		TABLE FORMATTER FUNCTIONS
	============================================================ */
	function getformatter($user, $formatter, $debug) {
		$sql = Processwire\wire('database')->prepare("SELECT data FROM tableformatter WHERE user = :user AND formattertype = :formatter LIMIT 1");
		$switching = array(':user' => $user, ':formatter' => $formatter); $withquotes = array(true, true);
		if ($debug) {
			return returnsqlquery($sql->queryString, $switching, $withquotes);
		} else {
			$sql->execute($switching);
			return $sql->fetchColumn();
		}
	}

	function addformatter($user, $formatter, $data, $debug) {
		$sql = Processwire\wire('database')->prepare("INSERT INTO tableformatter (user, formattertype, data) VALUES (:user, :formatter, :data)");
		$switching = array(':user' => $user, ':formatter' => $formatter, ':data' => $data); $withquotes = array(true, true, true);
		if ($debug) {
			return returnsqlquery($sql->queryString, $switching, $withquotes);
		} else {
			$sql->execute($switching);
			return array('sql' => returnsqlquery($sql->queryString, $switching, $withquotes), 'insertedid' => Processwire\wire('database')->lastInsertId());
		}
	}
	
	function does_tableformatterexist($userID, $formatter, $debug = false) {
		$q = (new QueryBuilder())->table('tableformatter');
		$q->field($q->expr('COUNT(*)'));
		$q->where('user', $userID);
		$q->where('formattertype', $formatter);
		$sql = Processwire\wire('database')->prepare($q->render());
		
		if ($debug) {
			return $q->generate_sqlquery($q->params);
		} else {
			$sql->execute($q->params);
			return $sql->fetchColumn();
		}
	}

	function checkformatterifexists($user, $formatter, $debug) {
		$sql = Processwire\wire('database')->prepare("SELECT COUNT(*) FROM tableformatter WHERE user = :user AND formattertype = :formatter");
		$switching = array(':user' => $user, ':formatter' => $formatter); $withquotes = array(true, true);
		if ($debug) {
			return returnsqlquery($sql->queryString, $switching, $withquotes);
		} else {
			$sql->execute($switching);
			return $sql->fetchColumn();
		}
	}
	
	function get_maxtableformatterid($userID, $formatter, $debug = false) {
		$q = (new QueryBuilder())->table('tableformatter');
		$q->field($q->expr('MAX(id)'));
		$q->where('user', $userID);
		$q->where('formattertype', $formatter);
		$sql = Processwire\wire('database')->prepare($q->render());
		
		if ($debug) {
			return $q->generate_sqlquery($q->params);
		} else {
			$sql->execute($q->params);
			return $sql->fetchColumn();
		}
	}
	
	function getmaxtableformatterid($user, $formatter, $debug) {
		$sql = Processwire\wire('database')->prepare("SELECT MAX(id) FROM tableformatter WHERE user = :user AND formattertype = :formatter");
		$switching = array(':user' => $user, ':formatter' => $formatter); $withquotes = array(true, true);
		if ($debug) {
			return returnsqlquery($sql->queryString, $switching, $withquotes);
		} else {
			$sql->execute($switching);
			return $sql->fetchColumn();
		}
	}

	function editformatter($user, $formatter, $data, $debug) {
		$sql = Processwire\wire('database')->prepare("UPDATE tableformatter SET data = :data WHERE user = :user AND formattertype =  :formatter");
		$switching = array(':user' => $user, ':formatter' => $formatter, ':data' => $data); $withquotes = array(true, true, true);
		if ($debug) {
			return returnsqlquery($sql->queryString, $switching, $withquotes);
		} else {
			$sql->execute($switching);
			return array('sql' => returnsqlquery($sql->queryString, $switching, $withquotes), 'affectedrows' => $sql->rowCount() ? true : false);
		}
	}
	
	function update_formatter($userID, $formatter, $data, $debug = false) {
		$q = (new QueryBuilder())->table('tableformatter');
		$q->mode('update');
		$q->set('data', $data);
		$q->where('user', $userID);
		$q->where('formattertype', $formatter);
		$sql = Processwire\wire('database')->prepare($q->render());
		
		if ($debug) {
			return $q->generate_sqlquery($q->params);
		} else {
			$sql->execute($q->params);
			return array('sql' => $q->generate_sqlquery($q->params), 'success' => $sql->rowCount() ? true : false, 'updated' => $sql->rowCount() ? true : false, 'querytype' => 'update');
		}
	}
	
	function create_formatter($userID, $formatter, $data, $debug = false) {
		$q = (new QueryBuilder())->table('tableformatter');
		$q->mode('insert');
		$q->set('data', $data);
		$q->set('user', $userID);
		$q->set('formattertype', $formatter);
		$sql = Processwire\wire('database')->prepare($q->render());
		
		if ($debug) {
			return $q->generate_sqlquery($q->params);
		} else {
			$sql->execute($q->params);
			return array('sql' => $q->generate_sqlquery($q->params), 'success' => Processwire\wire('database')->lastInsertId() > 0 ? true : false, 'id' => Processwire\wire('database')->lastInsertId(), 'querytype' => 'create');
		}
	}
	
	/* =============================================================
		USER CONFIGS FUNCTIONS
	============================================================ */
	function checkconfigifexists($user, $configuration, $debug) {
		$sql = Processwire\wire('database')->prepare("SELECT COUNT(*) FROM userconfigs WHERE user = :user AND configtype = :config");
		$switching = array(':user' => $user, ':config' => $configuration); $withquotes = array(true, true);
		if ($debug) {
			return returnsqlquery($sql->queryString, $switching, $withquotes);
		} else {
			$sql->execute($switching);
			return $sql->fetchColumn();
		}
	}

	function getconfiguration($user, $configuration, $debug) {
		$sql = Processwire\wire('database')->prepare("SELECT data FROM userconfigs WHERE user = :user AND configtype = :config LIMIT 1");
		$switching = array(':user' => $user, ':config' => $configuration); $withquotes = array(true, true);
		if ($debug) {
			return returnsqlquery($sql->queryString, $switching, $withquotes);
		} else {
			$sql->execute($switching);
			return $sql->fetchColumn();
		}
	}

  /* =============================================================
		CUSTOMER JSON CONFIGS
	============================================================ */
	function does_customerconfigexist($config, $debug = false) {
		$q = (new QueryBuilder())->table('customerconfigs');
		$q->field($q->expr('COUNT(*)'));
		$q->where('configtype', $config);
		$sql = Processwire\wire('database')->prepare($q->render());
		
		if ($debug) {
			return $q->generate_sqlquery($q->params);
		} else {
			$sql->execute($q->params);
			return $sql->fetchColumn();
		}
	}

	function get_customerconfig($config, $debug = false) {
		$q = (new QueryBuilder())->table('customerconfigs');
		$q->field('data');
		$q->where('configtype', $config);
		$q->limit(1);
		$sql = Processwire\wire('database')->prepare($q->render());
		
		if ($debug) {
			return $q->generate_sqlquery($q->params);
		} else {
			$sql->execute($q->params);
			return $sql->fetchColumn();
		}
	}
	
	function update_customerconfig($config, $data, $debug = false) {
		$q = (new QueryBuilder())->table('customerconfigs');
		$q->mode('update');
		$q->set('data', $data);
		$q->where('configtype', $config);
		$sql = Processwire\wire('database')->prepare($q->render());
		
		if ($debug) {
			return $q->generate_sqlquery($q->params);
		} else {
			$sql->execute($q->params);
			return array('sql' => $q->generate_sqlquery($q->params), 'success' => $sql->rowCount() ? true : false, 'updated' => $sql->rowCount() ? true : false, 'querytype' => 'update');
		}
	}
	
	function create_customerconfig($config, $data, $debug = false) {
		$q = (new QueryBuilder())->table('customerconfigs');
		$q->mode('insert');
		$q->set('data', $data);
		$q->set('configtype', $config);
		$sql = Processwire\wire('database')->prepare($q->render());
		
		if ($debug) {
			return $q->generate_sqlquery($q->params);
		} else {
			$sql->execute($q->params);
			return array('sql' => $q->generate_sqlquery($q->params), 'success' => Processwire\wire('database')->lastInsertId() > 0 ? true : false, 'id' => Processwire\wire('database')->lastInsertId(), 'querytype' => 'create');
		}
	}

	/* =============================================================
		LOGM FUNCTIONS
	============================================================ */
	function get_logmuser($loginID, $debug = false) {
		$q = (new QueryBuilder())->table('logm');
		$q->where('loginid', $loginID);

		$sql = Processwire\wire('database')->prepare($q->render());
		
		if ($debug) {
			return $q->generate_sqlquery($q->params);
		} else {
			$sql->execute($q->params);
			$sql->setFetchMode(PDO::FETCH_CLASS, 'LogmUser');
			return $sql->fetch();
		}
	}
	
	/* =============================================================
		LOGM FUNCTIONS
	============================================================ */
	function count_todaysbookings($sessionID, $custID = false, $shiptoID = false, $debug = false) {
		$q = (new QueryBuilder())->table('bookingd');
		$q->field('COUNT(*)');
		$q->where('bookdate', date('Ymd'));
		
		if (DplusWire::wire('user')->hascontactrestrictions) {
			$q->where('salesperson1', DplusWire::wire('user')->salespersonid);
		}
		
		if (!empty($custID)) {
			$q->where('custid', $custID);
			if (!empty($shiptoID)) {
				$q->where('shiptoid', $shiptoID);
			}
		}
		
		$sql = DplusWire::wire('database')->prepare($q->render());
		
		if ($debug) {
			return $q->generate_sqlquery($q->params);
		} else {
			$sql->execute($q->params);
			return $sql->fetchColumn();
		}
	}
	
	function get_userbookings($sessionID, $filter, $filtertypes, $interval = '', $debug = false) {
		$q = (new QueryBuilder())->table('bookingr');
		
		if (DplusWire::wire('user')->hascontactrestrictions) {
			$q->where('salesrep', DplusWire::wire('user')->salespersonid);
		}
		
		$q->generate_filters($filter, $filtertypes);

		switch ($interval) {
			case 'month':
				$q->field($q->expr("CAST(CONCAT(YEAR(bookdate), LPAD(MONTH(bookdate), 2, '0'), '01') AS UNSIGNED) as bookdate"));
				$q->field('SUM(amount) as amount');
				$q->group('YEAR(bookdate), MONTH(bookdate)');
				break;
			case 'day':
				$q->field('bookingr.*');
				$q->field('SUM(amount) as amount');
				$q->group('bookdate');
				break;
		}
		
		$sql = DplusWire::wire('database')->prepare($q->render());
		if ($debug) {
			return $q->generate_sqlquery($q->params);
		} else {
			$sql->execute($q->params);
			return $sql->fetchAll(PDO::FETCH_ASSOC);
		}
	}
	
	function get_bookingtotalsbycustomer($sessionID, $filter, $filtertypes, $interval = '', $debug = false) {
		$q = (new QueryBuilder())->table('bookingc');
		
		if (DplusWire::wire('user')->hascontactrestrictions) {
			$q->where('salesrep', DplusWire::wire('user')->salespersonid);
		}
		
		$q->generate_filters($filter, $filtertypes);

		switch ($interval) {
			case 'month':
				$q->field('custid');
				$q->field('SUM(amount) as amount');
				$q->group('custid');
				break;
			case 'day':
				$q->field('bookingc.*');
				$q->field('SUM(amount) as amount');
				$q->group('bookdate');
				break;
		}
		
		$sql = DplusWire::wire('database')->prepare($q->render());
		if ($debug) {
			return $q->generate_sqlquery($q->params);
		} else {
			$sql->execute($q->params);
			return $sql->fetchAll(PDO::FETCH_ASSOC);
		}
	}
	
	function count_daybookingordernumbers($sessionID, $date, $custID = false, $shiptoID = false, $debug = false) {
		$q = (new QueryBuilder())->table('bookingd');
		$q->field($q->expr('COUNT(DISTINCT(salesordernbr))'));

		$q->where('bookdate', date('Ymd', strtotime($date)));
		
		if (DplusWire::wire('user')->hascontactrestrictions) {
			$q->where('salesperson1', DplusWire::wire('user')->salespersonid);
		}
		
		if (!empty($custID)) {
			$q->where('custid', $custID);
			if (!empty($shiptoID)) {
				$q->where('shiptoid', $shiptoID);
			}
		}
		
		$sql = DplusWire::wire('database')->prepare($q->render());
		
		if ($debug) {
			return $q->generate_sqlquery($q->params);
		} else {
			$sql->execute($q->params);
			return $sql->fetchColumn();
		}
	}
	
	function get_daybookingordernumbers($sessionID, $date, $custID = false, $shiptoID = false, $debug = false) {
		$q = (new QueryBuilder())->table('bookingd');
		$q->field($q->expr('DISTINCT(salesordernbr)'));
		$q->field('bookdate');
		$q->field('custid');
		$q->field('shiptoid');
		$q->where('bookdate', date('Ymd', strtotime($date)));
		
		if (DplusWire::wire('user')->hascontactrestrictions) {
			$q->where('salesperson1', DplusWire::wire('user')->salespersonid);
		}
		
		if (!empty($custID)) {
			$q->where('custid', $custID);
			if (!empty($shiptoID)) {
				$q->where('shiptoid', $shiptoID);
			}
		}
		
		$sql = DplusWire::wire('database')->prepare($q->render());
		
		if ($debug) {
			return $q->generate_sqlquery($q->params);
		} else {
			$sql->execute($q->params);
			return $sql->fetchAll(PDO::FETCH_ASSOC);
		}
	}
	
	function get_bookingdayorderdetails($sessionID, $ordn, $date, $custID = false, $shiptoID = false, $debug = false) {
		$q = (new QueryBuilder())->table('bookingd');
		$q->where('bookdate', date('Ymd', strtotime($date)));
		$q->where('salesordernbr', $ordn);
		
		if (DplusWire::wire('user')->hascontactrestrictions) {
			$q->where('salesperson1', DplusWire::wire('user')->salespersonid);
		}
		
		if (!empty($custID)) {
			$q->where('custid', $custID);
			if (!empty($shiptoID)) {
				$q->where('shiptoid', $shiptoID);
			}
		}
		
		$sql = DplusWire::wire('database')->prepare($q->render());
		
		if ($debug) {
			return $q->generate_sqlquery($q->params);
		} else {
			$sql->execute($q->params);
			return $sql->fetchAll(PDO::FETCH_ASSOC);
		}
	}
	
	function get_customerbookings($sessionID, $custID, $shipID, $filter, $filtertypes, $interval = '', $debug = false) {
		$q = (new QueryBuilder())->table('bookingc');
		$q->where('custid', $custID);
		if (!empty($shipID)) {
			$q->where('shiptoid', $shipID);
		}
		
		if (DplusWire::wire('user')->hascontactrestrictions) {
			$q->where('salesrep', DplusWire::wire('user')->salespersonid);
		}
		
		$q->generate_filters($filter, $filtertypes);

		switch ($interval) {
			case 'month':
				$q->field($q->expr("CAST(CONCAT(YEAR(bookdate), LPAD(MONTH(bookdate), 2, '0'), '01') AS UNSIGNED) as bookdate"));
				$q->field('SUM(amount) as amount');
				$q->group('YEAR(bookdate), MONTH(bookdate)');
				break;
			case 'day':
				$q->field('bookingc.*');
				$q->field('SUM(amount) as amount');
				$q->group('bookdate');
				break;
		}
		
		$sql = DplusWire::wire('database')->prepare($q->render());
		if ($debug) {
			return $q->generate_sqlquery($q->params);
		} else {
			$sql->execute($q->params);
			return $sql->fetchAll(PDO::FETCH_ASSOC);
		}
	}
	
	function get_customerdaybookingordernumbers($sessionID, $date, $custID, $shipID, $debug = false) {
		$q = (new QueryBuilder())->table('bookingd');
		$q->field($q->expr('DISTINCT(salesordernbr)'));
		$q->field('bookdate');
		$q->where('bookdate', date('Ymd', strtotime($date)));
		
		if (DplusWire::wire('user')->hascontactrestrictions) {
			$q->where('salesperson1', DplusWire::wire('user')->salespersonid);
		}
		
		$q->where('custid', $custID);
		if (!empty($shipID)) {
			$q->where('shiptoid', $shipID);
		}
		
		$sql = DplusWire::wire('database')->prepare($q->render());
		
		if ($debug) {
			return $q->generate_sqlquery($q->params);
		} else {
			$sql->execute($q->params);
			return $sql->fetchAll(PDO::FETCH_ASSOC);
		}
	}
	
	function count_customerdaybookingordernumbers($sessionID, $date, $custID, $shipID, $debug = false) {
		$q = (new QueryBuilder())->table('bookingd');
		$q->field($q->expr('COUNT(DISTINCT(salesordernbr))'));

		$q->where('bookdate', date('Ymd', strtotime($date)));
		
		if (DplusWire::wire('user')->hascontactrestrictions) {
			$q->where('salesperson1', DplusWire::wire('user')->salespersonid);
		}
		
		$q->where('custid', $custID);
		if (!empty($shipID)) {
			$q->where('shiptoid', $shipID);
		}
		
		$sql = DplusWire::wire('database')->prepare($q->render());
		
		if ($debug) {
			return $q->generate_sqlquery($q->params);
		} else {
			$sql->execute($q->params);
			return $sql->fetchColumn();
		}
	}
	
	function count_customertodaysbookings($sessionID, $custID, $shipID, $debug = false) {
		$q = (new QueryBuilder())->table('bookingc');
		$q->field('COUNT(*)');
		
		$q->where('bookdate', date('Ymd'));
		
		if (DplusWire::wire('user')->hascontactrestrictions) {
			$q->where('salesperson1', DplusWire::wire('user')->salespersonid);
		}
		
		$q->where('custid', $custID);
		if (!empty($shipID)) {
			$q->where('shiptoid', $shipID);
		}
		
		$sql = DplusWire::wire('database')->prepare($q->render());
		
		if ($debug) {
			return $q->generate_sqlquery($q->params);
		} else {
			$sql->execute($q->params);
			return $sql->fetchColumn();
		}
	}
	
	function get_bookingtotalsbyshipto($sessionID, $custID, $shipID, $filter, $filtertypes, $interval = '', $debug = false) {
		$q = (new QueryBuilder())->table('bookingc');
		
		$q->where('custid', $custID);
		if (!empty($shipID)) {
			$q->where('shiptoid', $shipID);
		}
		
		if (DplusWire::wire('user')->hascontactrestrictions) {
			$q->where('salesrep', DplusWire::wire('user')->salespersonid);
		} else {
			
		}
		
		$q->generate_filters($filter, $filtertypes);

		switch ($interval) {
			case 'month':
				$q->field('custid');
				$q->field('shiptoid');
				$q->field('SUM(amount) as amount');
				$q->group('custid,shiptoid');
				break;
			case 'day':
				$q->field('bookingc.*');
				$q->field('SUM(amount) as amount');
				$q->group('bookdate');
				break;
		}
		
		$sql = DplusWire::wire('database')->prepare($q->render());
		if ($debug) {
			return $q->generate_sqlquery($q->params);
		} else {
			$sql->execute($q->params);
			return $sql->fetchAll(PDO::FETCH_ASSOC);
		}
	}
