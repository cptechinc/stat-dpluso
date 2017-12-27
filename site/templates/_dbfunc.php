<?php
	use atk4\dsql\Query;
/* =============================================================
	LOGIN FUNCTIONS
============================================================ */
	function is_validlogin($sessionID) {
		$sql = wire('database')->prepare("SELECT IF(validlogin = 'Y',1,0) FROM logperm WHERE sessionid = :sessionID LIMIT 1");
		$switching = array(':sessionID' => $sessionID);
		$sql->execute($switching);
		return $sql->fetchColumn();
	}

	function get_loginerrormsg($sessionID) {
		$sql = wire('database')->prepare("SELECT errormsg FROM logperm WHERE sessionid = :sessionID");
		$switching = array(':sessionID' => $sessionID);
		$sql->execute($switching);
		return $sql->fetchColumn();
	}

	function get_loginrecord($sessionID) {
		$sql = wire('database')->prepare("SELECT IF(restrictcustomers = 'Y',1,0) as restrictcustomer, IF(restrictaccess = 'Y',1,0) as restrictuseraccess, logperm.* FROM logperm WHERE sessionid = :sessionID");
		$switching = array(':sessionID' => $sessionID);
		$sql->execute($switching);
		return $sql->fetch(PDO::FETCH_ASSOC);
	}
/* =============================================================
	PERMISSION FUNCTIONS
============================================================ */
	function has_dpluspermission($loginID, $dplusfunction, $debug = false) {
		$q = (new QueryBuilder())->table('funcperm');
		$q->field($q->expr("IF(permission = 'Y',1,0)"));
		$q->where('loginid', $loginID);
		$q->where('function', $dplusfunction);
		$sql = wire('database')->prepare($q->render());
		
		if ($debug) {
			return $q->generate_sqlquery($q->params);
		} else {
			$sql->execute($q->params);
			return $sql->fetch();
		}
	}
/* =============================================================
	CUSTOMER FUNCTIONS
============================================================ */
	function can_accesscustomer($loginID, $restrictions, $custID, $debug) {
		$SHARED_ACCOUNTS = wire('config')->sharedaccounts;
		if ($restrictions) {
			$sql = wire('database')->prepare("SELECT COUNT(*) FROM (SELECT * FROM custperm WHERE custid = :custID) t WHERE loginid = :loginID OR loginid = :shared");
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
		$SHARED_ACCOUNTS = wire('config')->sharedaccounts;
		if ($restrictions) {
			$sql = wire('database')->prepare("SELECT COUNT(*) FROM (SELECT * FROM custperm WHERE custid = :custID AND shiptoid = :shipID) t WHERE loginid = :loginID OR loginid = :shared");
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

	function get_customername($custID) {
		$sql = wire('database')->prepare("SELECT name FROM custindex WHERE custid = :custID LIMIT 1");
		$switching = array(':custID' => $custID);
		$sql->execute($switching);
		return $sql->fetchColumn();
	}

	function get_shiptoname($custID, $shipID, $debug) {
		$sql = wire('database')->prepare("SELECT name FROM custindex WHERE custid = :custID AND shiptoid = :shipID LIMIT 1");
		$switching = array(':custID' => $custID, ':shipID' => $shipID); $withquotes = array(true, true);
		if ($debug) {
			return returnsqlquery($sql->queryString, $switching, $withquotes);
		} else {
			$sql->execute($switching);
			return $sql->fetchColumn();
		}
	}

	function get_customerinfo($sessionID, $custID, $debug) {
		$sql = wire('database')->prepare("SELECT custindex.*, customer.dateentered FROM custindex JOIN customer ON custindex.custid = customer.custid WHERE custindex.custid = :custID AND customer.sessionid = :sessionID LIMIT 1");
		$switching = array(':sessionID' => $sessionID, ':custID' => $custID); $withquotes = array(true, true);
		if ($debug) {
			return returnsqlquery($sql->queryString, $switching, $withquotes);
		} else {
			$sql->execute($switching);
			return $sql->fetch(PDO::FETCH_ASSOC);
		}
	}

	function get_firstcustindexrecord($debug) {
		$sql = wire('database')->prepare("SELECT * FROM custindex LIMIT 1");
		if ($debug) {
			return $sql->queryString;
		} else {
			$sql->execute();
			return $sql->fetch(PDO::FETCH_ASSOC);
		}
	}

	function count_shiptos($custID, $loginID, $restrictions, $debug = false) { // TODO use QueryBuilder
		$SHARED_ACCOUNTS = wire('config')->sharedaccounts;
		if ($restrictions) {
			$sql = wire('database')->prepare("SELECT COUNT(*) FROM (SELECT * FROM custperm WHERE custid = :custID AND shiptoid != '') t WHERE loginid = :loginID OR loginid = :shared ");
			$switching = array(':custID' => $custID, ':loginID' => $loginID, ':shared' => $SHARED_ACCOUNTS);
			$withquotes = array(true, true, true);
		} else {
			$sql = wire('database')->prepare("SELECT COUNT(*) FROM custperm WHERE custid = :custID AND shiptoid != ''");
			$switching = array(':custID' => $custID); $withquotes = array(true);
		}
		if ($debug) {
			return returnsqlquery($sql->queryString, $switching, $withquotes);
		} else {
			$sql->execute($switching);
			return $sql->fetchColumn();
		}
	}

	function get_shiptoinfo($custID, $shipID, $debug) {
		$sql = wire('database')->prepare("SELECT * FROM custindex WHERE custid = :custID AND shiptoid = :shipID LIMIT 1");
		$switching = array(':custID' => $custID, ':shipID' => $shipID); $withquotes = array(true, true);
		if ($debug) {
			return returnsqlquery($sql->queryString, $switching, $withquotes);
		} else {
			$sql->execute($switching);
			return $sql->fetch(PDO::FETCH_ASSOC);
		}
	}

	function get_customershiptos($custID, $loginID, $restrictions, $debug = false) { // TODO use QueryBuilder
		$SHARED_ACCOUNTS = wire('config')->sharedaccounts;
		if ($restrictions) {
			$sql = wire('database')->prepare("SELECT * FROM custindex WHERE (custid, shiptoid) IN (SELECT custid, shiptoid FROM (SELECT * FROM custperm WHERE custid = :custID AND shiptoid != '') t WHERE loginid = :loginID OR loginid = :shared) GROUP BY custid, shiptoid");
			$switching = array(':custID' => $custID, ':loginID' => $loginID, ':shared' => $SHARED_ACCOUNTS);
			$withquotes = array(true, true, true);
		} else {
			$sql = wire('database')->prepare("SELECT * FROM custindex WHERE custid = :custID AND shiptoid != '' GROUP BY custid, shiptoid");
			$switching = array(':custID' => $custID); $withquotes = array(true);
		}

		if ($debug) {
			return returnsqlquery($sql->queryString, $switching, $withquotes);
		} else {
			$sql->execute($switching);
			$sql->setFetchMode(PDO::FETCH_CLASS, 'Customer');
			return $sql->fetchAll();
		}
	}

	function get_customercontacts($loginID, $restrictions, $custID, $debug = false) {
		$SHARED_ACCOUNTS = wire('config')->sharedaccounts;
		if ($restrictions) {
			$sql = wire('database')->prepare("SELECT * FROM custindex WHERE (custid, shiptoid) IN (SELECT custid, shiptoid FROM (SELECT * FROM custperm WHERE custid = :custID) t WHERE loginid = :loginID OR loginid = :shared)");
			$switching = array(':custID' => $custID, ':loginID' => $loginID, ':shared' => $SHARED_ACCOUNTS);
			$withquotes = array(true, true, true);
		} else {
			$sql = wire('database')->prepare("SELECT * FROM custindex WHERE custid = :custID");
			$switching = array(':custID' => $custID); $withquotes = array(true);
		}

		if ($debug) {
			return returnsqlquery($sql->queryString, $switching, $withquotes);
		} else {
			$sql->execute($switching);
			$sql->setFetchMode(PDO::FETCH_CLASS, 'Contact');
			return $sql->fetchAll();
		}
	}

	function can_accesscustomercontact($loginID, $restrictions, $custID, $shipID, $contactID, $debug) {
		$SHARED_ACCOUNTS = wire('config')->sharedaccounts;
		if ($restrictions) {
			$sql = wire('database')->prepare("SELECT COUNT(*) FROM custindex WHERE (custid, shiptoid) IN (SELECT custid, shiptoid FROM (SELECT * FROM custperm WHERE custid = :custID) t WHERE loginid = :loginID OR loginid = :shared) AND shiptoid = :shipID AND contact = :contactID");
			$switching = array(':custID' => $custID, ':loginID' => $loginID, ':shared' => $SHARED_ACCOUNTS, ':shipID' => $shipID, ':contactID' => $contactID);
			$withquotes = array(true, true, true, true, true);
		} else {
			$sql = wire('database')->prepare("SELECT COUNT(*) FROM custindex WHERE custid = :custID AND shiptoid = :shipID AND contact = :contactID");
			$switching = array(':custID' => $custID, ':shipID' => $shipID, ':contactID' => $contactID);
			$withquotes = array(true, true, true);
		}
		$sql->execute($switching);
		if ($debug) { return returnsqlquery($sql->queryString, $switching, $withquotes); } else { if ($sql->fetchColumn() > 0){return true;} else {return false; } }
	}

	function get_customercontact($custID, $shipID = '', $contactID = '', $debug = false) {
		$q = (new QueryBuilder())->table('custindex');
		$q->limit(1);
		$q->where('custid', $custID);
		$q->where('shiptoid', $shipID);
		if (!empty($contactID)) {
			$q->where('contact', $contactID);
		}
		$sql = wire('database')->prepare($q->render());
		
		if ($debug) {
			return $q->generate_sqlquery($q->params);
		} else {
			$sql->execute($q->params);
			$sql->setFetchMode(PDO::FETCH_CLASS, 'Contact');
			return $sql->fetch();
		}
	}

	function edit_customercontact($custID, $shipID, $contactID, $contact, $debug) {
		$originalcontact = get_customercontact($custID, $shipID, $contactID, false);
		$query = returnpreppedquery($originalcontact, $contact);
		$sql = wire('database')->prepare("UPDATE custindex SET ".$query['setstatement']." WHERE custid = :custID AND shiptoid = :shipID AND contact = :originalcontactID");
		$query['switching'][':custID'] = $custID; $query['switching'][':shipID'] = $shipID; $query['switching'][':originalcontactID'] = $contactID;
		$query['withquotes'][] = true; $query['withquotes'][]= true; $query['withquotes'][] = true;
		if ($debug) {
			return returnsqlquery($sql->queryString, $query['switching'], $query['withquotes']);
		} else {
			$returnquery = returnsqlquery($sql->queryString, $query['switching'], $query['withquotes']);
			if ($query['changecount'] > 0) {
				$sql->execute($query['switching']);
				$success = $sql->rowCount();
				return $success ? array('error' => false, 'changes' => $query['changecount'], 'sql' => $returnquery) : array('error' => true, 'changes' => 0, 'sql' => $returnquery);
			}
			return array('error' => false, 'changes' => 0, 'sql' => $returnquery);
		}
	}

	function get_customersalesperson($custID, $shipID, $debug) {
		$sql = wire('database')->prepare("SELECT splogin1 FROM custindex WHERE custid = :custID AND shiptoid = :shipID LIMIT 1");
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
		$SHARED_ACCOUNTS = wire('config')->sharedaccounts;
		$limiting = returnlimitstatement($limit, $page);

		if ($restrictions) {
			$sql = wire('database')->prepare("SELECT * FROM custindex WHERE custid IN (SELECT DISTINCT(custid) FROM custperm WHERE loginid = :loginID OR loginid = :shared) GROUP BY custid ".$limiting);
			$switching = array(':loginID' => $loginID, ':shared' => $SHARED_ACCOUNTS);
			$withquotes = array(true, true);
		} else {
			$sql = wire('database')->prepare("SELECT * FROM custindex WHERE shiptoid = '' GROUP BY custid " . $limiting);
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
		$SHARED_ACCOUNTS = wire('config')->sharedaccounts;
		if ($restrictions) {
			$sql = wire('database')->prepare("SELECT COUNT(DISTINCT(custid)) FROM custindex WHERE custid IN (SELECT DISTINCT(custid) FROM custperm WHERE loginid = :loginID OR loginid = :shared)");
			$switching = array(':loginID' => $loginID, ':shared' => $SHARED_ACCOUNTS);
			$withquotes = array(true, true);
		} else {
			$sql = wire('database')->prepare("SELECT COUNT(DISTINCT(custid)) FROM custindex WHERE shiptoid = ''");
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
		$SHARED_ACCOUNTS = wire('config')->sharedaccounts;
		$limiting = returnlimitstatement($limit, $page);
		$search = '%'.str_replace(' ', '%', str_replace('-', '', $keyword)).'%';
	
		if ($restrictions) {
			if (wire('config')->cptechcustomer == 'stempf') {
				$sql = wire('database')->prepare("SELECT * FROM custindex WHERE (custid, shiptoid) IN (SELECT custid, shiptoid FROM custperm WHERE loginid = :loginID OR loginid = :shared) AND UCASE(REPLACE(CONCAT(custid, ' ', name, ' ', shiptoid, ' ', addr1, ' ', ccity, ' ', cst, ' ', czip, ' ', cphone, ' ', contact, ' ', source, ' ', cphext), '-', '')) LIKE UCASE(:search) GROUP BY custid, shiptoid ORDER BY custid <> '$keyword' $limiting");
			} else {
				$sql = wire('database')->prepare("SELECT * FROM custindex WHERE (custid, shiptoid) IN (SELECT custid, shiptoid FROM custperm WHERE loginid = :loginID OR loginid = :shared) AND UCASE(REPLACE(CONCAT(custid, ' ', name, ' ', shiptoid, ' ', addr1, ' ', ccity, ' ', cst, ' ', czip, ' ', cphone, ' ', contact, ' ', source, ' ', cphext), '-', '')) LIKE UCASE(:search) ORDER BY custid <> '$keyword' $limiting");
			}
			$switching = array(':loginID' => $loginID, ':shared' => $SHARED_ACCOUNTS, ':search' => $search);
			$withquotes = array(true, true, true);
		} else {
			if (wire('config')->cptechcustomer == 'stempf') {
				$sql = wire('database')->prepare("SELECT * FROM custindex WHERE UCASE(REPLACE(CONCAT(custid, ' ', name, ' ', shiptoid, ' ', addr1, ' ', ccity, ' ', cst, ' ', czip, ' ', cphone, ' ', contact, ' ', source, ' ', cphext), '-', '')) LIKE UCASE(:search) GROUP BY custid, shiptoid ORDER BY custid <> '$keyword' $limiting");
			} else {
				$sql = wire('database')->prepare("SELECT * FROM custindex WHERE UCASE(REPLACE(CONCAT(custid, ' ', name, ' ', shiptoid, ' ', addr1, ' ', ccity, ' ', cst, ' ', czip, ' ', cphone, ' ', contact, ' ', source, ' ', cphext), '-', '')) LIKE UCASE(:search) ORDER BY custid <> '$keyword' $limiting");
			}
			$switching = array(':search' => $search); $withquotes = array(true);
		}
	
		if ($debug) {
			return returnsqlquery($sql->queryString, $switching, $withquotes);
		} else {
			$sql->execute($switching);
			$sql->setFetchMode(PDO::FETCH_CLASS, 'Contact');
			return $sql->fetchAll();
		}
	}
	
	function count_searchcustindex($loginID, $restrictions, $keyword, $debug) {
		$SHARED_ACCOUNTS = wire('config')->sharedaccounts;
		$search = '%'.str_replace(' ', '%', str_replace('-', '', $keyword)).'%';

		if ($restrictions) {
			if (wire('config')->cptechcustomer == 'stempf') {
				$sql = wire('database')->prepare("SELECT COUNT(*) FROM (SELECT * FROM custindex GROUP BY custid, shiptoid) t WHERE (custid, shiptoid) IN (SELECT custid, shiptoid FROM custperm WHERE loginid = :loginID OR loginid = :shared) AND UCASE(REPLACE(CONCAT(custid, ' ', name, ' ', shiptoid, ' ', addr1, ' ', ccity, ' ', cst, ' ', czip, ' ', cphone, ' ', contact, ' ', source, ' ', cphext), '-', '')) LIKE UCASE(:search)");
			} else {
				$sql = wire('database')->prepare("SELECT COUNT(*) FROM custindex WHERE (custid, shiptoid) IN (SELECT custid, shiptoid FROM custperm WHERE loginid = :loginID OR loginid = :shared) AND UCASE(REPLACE(CONCAT(custid, ' ', name, ' ', shiptoid, ' ', addr1, ' ', ccity, ' ', cst, ' ', czip, ' ', cphone, ' ', contact, ' ', source, ' ', cphext), '-', '')) LIKE UCASE(:search)");
			}
			$switching = array(':loginID' => $loginID, ':shared' => $SHARED_ACCOUNTS, ':search' => $search);
			$withquotes = array(true, true, true, true);
		} else {
			if (wire('config')->cptechcustomer == 'stempf') {
				$sql = wire('database')->prepare("SELECT COUNT(*) FROM (SELECT * FROM custindex GROUP BY custid, shiptoid) t WHERE UCASE(REPLACE(CONCAT(custid, ' ', name, ' ', shiptoid, ' ', addr1, ' ', ccity, ' ', cst, ' ', czip, ' ', cphone, ' ', contact, ' ', source, ' ', cphext), '-', '')) LIKE UCASE(:search)");
			} else {
				$sql = wire('database')->prepare("SELECT COUNT(*) FROM custindex WHERE UCASE(REPLACE(CONCAT(custid, ' ', name, ' ', shiptoid, ' ', addr1, ' ', ccity, ' ', cst, ' ', czip, ' ', cphone, ' ', contact, ' ', source, ' ', cphext), '-', '')) LIKE UCASE(:search)");
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

	function get_topxsellingcustomers($loginID, $numberofcustomers, $restrictions, $debug = false) {
		$SHARED_ACCOUNTS = wire('config')->sharedaccounts;
		if ($restrictions) {
			$sql = wire('database')->prepare("SELECT custid, shiptoid, name, amountsold, timesold, lastsaledate FROM custindex WHERE (custid, shiptoid) IN (SELECT custid, shiptoid FROM custperm WHERE loginid = :loginID OR loginid = :shared) GROUP BY custid, shiptoid ORDER BY CAST(amountsold as Decimal(10,8)) DESC LIMIT $numberofcustomers");
			$switching = array(':loginID' => $loginID, ':shared' => $SHARED_ACCOUNTS); $withquotes = array(true, true);
		} else {
			$sql = wire('database')->prepare("SELECT custid, shiptoid, name, amountsold, timesold, lastsaledate FROM custindex GROUP BY custid, shiptoid ORDER BY CAST(amountsold as Decimal(10,8)) DESC LIMIT $numberofcustomers");
			$switching = array(); $withquotes = array();
		}

		if ($debug) {
			return returnsqlquery($sql->queryString, $switching, $withquotes);
		} else {
			$sql->execute($switching);
			$sql->setFetchMode(PDO::FETCH_CLASS, 'Customer');
			return $sql->fetchAll();
		}
	}

	function insert_newcustindexrecord($customer, $debug) {
		$query = returninsertlinks($customer);
		$sql = wire('database')->prepare("INSERT INTO custindex (".$query['columnlist'].") VALUES (".$query['valuelist'].")");
		$switching = $query['switching']; $withquotes = $query['withquotes'];
		if ($debug) {
			return returnsqlquery($sql->queryString, $switching, $withquotes);
		} else {
			$sql->execute($switching);
			return returnsqlquery($sql->queryString, $switching, $withquotes);
		}
	}

	function getmax_custindexrecnbr() {
		$sql = wire('database')->prepare("SELECT MAX(recno) FROM custindex");
		$sql->execute();
		return $sql->fetchColumn();
	}

	function edit_custindexcustid($originalcustID, $newcustID) {
		$sql = wire('database')->prepare("UPDATE custindex SET custid = :newcustid WHERE custid = :originalcustid");
		$switching = array(':newcustid' => $newcustID, ':originalcustid' => $originalcustID); $withquotes = array(true, true);
		$sql->execute($switching);
		return returnsqlquery($sql->queryString, $switching, $withquotes);
	}

/* =============================================================
	ORDERS FUNCTIONS
============================================================ */
	function count_salesreporders($sessionID, $debug) {
		$sql = wire('database')->prepare("SELECT IF(COUNT(DISTINCT(custid)) > 1,COUNT(*),0) as count FROM ordrhed WHERE sessionid = :sessionID AND type = :type");
		$switching = array(':sessionID' => $sessionID, ':type' => 'O'); $withquotes = array(true, true);
		if ($debug) {
			return returnsqlquery($sql->queryString, $switching, $withquotes);
		} else {
			$sql->execute($switching);
			return $sql->fetchColumn();
		}
	}

	function get_salesrepordersorderdate($sessionID, $limit = 10, $page = 1, $sortrule, $useclass, $debug) {
		$limiting = returnlimitstatement($limit, $page);
		$sql = wire('database')->prepare("SELECT orderdate, STR_TO_DATE(orderdate, '%m/%d/%Y') as dateoforder, orderno, custpo, shiptoid, sname, saddress, saddress2, scity, sst, szip, havenote,
					status, havetrk, havedoc, odrsubtot, odrtax, odrfrt, odrmis, odrtotal, error, errormsg, shipdate, custid, custname, invdate, editord FROM ordrhed
					WHERE sessionid = :sessionID AND type = :type ORDER BY dateoforder $sortrule " . $limiting);
		$switching = array(':sessionID' => $sessionID, ':type' => 'O'); $withquotes = array(true, true);
		if ($debug) {
			return returnsqlquery($sql->queryString, $switching, $withquotes);
		} else {
			$sql->execute($switching);
			if ($useclass) {
				$sql->setFetchMode(PDO::FETCH_CLASS, 'SalesOrder');
				return $sql->fetchAll();
			}
			return $sql->fetchAll(PDO::FETCH_ASSOC);
		}
	}

	function get_salesrepordersorderby($sessionID, $limit = 10, $page = 1, $sortrule, $orderby, $useclass, $debug) {
		$limiting = returnlimitstatement($limit, $page);
		$sql = wire('database')->prepare("SELECT ordrhed.*, CAST(odrsubtot AS DECIMAL(8,2)) AS subtotal FROM ordrhed WHERE sessionid = :sessionID  AND type = :type ORDER BY $orderby $sortrule " . $limiting);
		$switching = array(':sessionID' => $sessionID, ':type' => 'O'); $withquotes = array(true, true);
		if ($debug) {
			return returnsqlquery($sql->queryString, $switching, $withquotes);
		} else {
			$sql->execute($switching);
			if ($useclass) {
				$sql->setFetchMode(PDO::FETCH_CLASS, 'SalesOrder');
				return $sql->fetchAll();
			}
			return $sql->fetchAll(PDO::FETCH_ASSOC);
		}
	}

	function get_salesreporders($sessionID, $limit = 10, $page = 1, $useclass, $debug) {
		$limiting = returnlimitstatement($limit, $page);
		$sql = wire('database')->prepare("SELECT * FROM ordrhed WHERE sessionid = :sessionID AND type = :type ".$limiting);
		$switching = array(':sessionID' => $sessionID, ':type' => 'O'); $withquotes = array(true, true);
		if ($debug) {
			return returnsqlquery($sql->queryString, $switching, $withquotes);
		} else {
			$sql->execute($switching);
			if ($useclass) {
				$sql->setFetchMode(PDO::FETCH_CLASS, 'SalesOrder');
				return $sql->fetchAll();
			}
			return $sql->fetchAll(PDO::FETCH_ASSOC);
		}
	}

	function count_customerorders($sessionID, $custID, $debug) {
		$sql = wire('database')->prepare("SELECT COUNT(*) as count FROM ordrhed WHERE sessionid = :sessionID AND custid = :custID AND type = 'O'");
		$switching = array(':sessionID' => $sessionID, ':custID' => $custID); $withquotes = array(true, true);
		if ($debug) {
			return returnsqlquery($sql->queryString, $switching, $withquotes);
		} else {
			$sql->execute($switching);
			return $sql->fetchColumn();
		}
	}

	function get_cust_orders($sessionID, $custID, $limit = 10, $page = 1, $debug) {
		$limiting = returnlimitstatement($limit, $page);
		$sql = wire('database')->prepare("SELECT * FROM ordrhed WHERE sessionid = :sessionID AND custid = :custID AND type = 'O' ".$limiting);
		$switching = array(':sessionID' => $sessionID, ':custID' => $custID); $withquotes = array(true, true);
		if ($debug) {
			return returnsqlquery($sql->queryString, $switching, $withquotes);
		} else {
			$sql->execute($switching);
			return $sql->fetchAll(PDO::FETCH_ASSOC);
		}
	}

	function get_customerordersorderby($sessionID, $custID, $limit = 10, $page = 1, $sortrule, $orderby, $debug) {
		$q = (new QueryBuilder())->table('ordrhed');
		$q->where('sessionid', $sessionID);
		$q->where('custid', $custID);
		$q->where('type', 'O');
		$q->limit($limit, $q->generate_offset($page, $limit));
		$q->order($q->generate_orderby($orderby));
		$sql = wire('database')->prepare($q->render());
		
		if ($debug) {
			return $q->generate_sqlquery($q->params);
		} else {
			$sql->execute($q->params);
			$sql->setFetchMode(PDO::FETCH_CLASS, 'SalesOrder');
			return $sql->fetchAll();
		}
	}

	function get_customerordersorderdate($sessionID, $custID, $limit = 10, $page = 1, $sortrule, $useclass = false, $debug) {
		$limiting = returnlimitstatement($limit, $page);
		$sql = wire('database')->prepare("SELECT orderdate, STR_TO_DATE(orderdate, '%m/%d/%Y') as dateoforder, orderno, custpo, shiptoid, sname, saddress, saddress2, scity, sst, szip, havenote, status, havetrk, havedoc, odrsubtot, odrtax, odrfrt, odrmis, odrtotal, error, errormsg, shipdate, custid, custname, invdate, editord FROM ordrhed WHERE sessionid = :sessionID AND custid = :custID AND type = 'O' ORDER BY dateoforder $sortrule ".$limiting);
		$switching = array(':sessionID' => $sessionID, ':custID' => $custID); $withquotes = array(true, true);
		if ($debug) {
			return returnsqlquery($sql->queryString, $switching, $withquotes);
		} else {
			$sql->execute($switching);
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
		$sql = wire('database')->prepare($q->render());
		
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
		$sql = wire('database')->prepare($q->render());
		
		if ($debug) {
			return $q->generate_sqlquery($q->params);
		} else {
			$sql->execute($q->params);
			return $sql->fetchColumn();
		}
	}

	function get_orderdetails($sessionID, $ordn, $useclass = false, $debug) {
		$sql = wire('database')->prepare("SELECT * FROM ordrdet WHERE sessionid = :sessionID AND orderno = :ordn");
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
		$sql = wire('database')->prepare("SELECT COUNT(*) FROM ordlock WHERE sessionid = :sessionID");
		$switching = array(':sessionID' => $sessionID);
		$sql->execute($switching);
		return $sql->fetchColumn() > 0 ? true : false;
	}

	function getlockedordn($sessionID) {
		$sql = wire('database')->prepare("SELECT orderno FROM ordlock WHERE sessionid = :sessionID LIMIT 1");
		$switching = array(':sessionID' => $sessionID);
		$sql->execute($switching);
		return $sql->fetchColumn();
	}

	function is_orderlocked($sessionID, $ordn) {
		$sql = wire('database')->prepare("SELECT COUNT(*) FROM ordlock WHERE sessionid = :sessionID AND orderno = :ordn LIMIT 1");
		$switching = array(':sessionID' => $sessionID, ':ordn' => $ordn);
		$sql->execute($switching);
		return $sql->fetchColumn();
	}
	
	function get_nextorderlock($sessionID) {
		$sql = wire('database')->prepare("SELECT MAX(recno) FROM ordlock WHERE sessionid = :sessionID LIMIT 1");
		$switching = array(':sessionID' => $sessionID);
		$sql->execute($switching);
		return (intval($sql->fetchColumn()) + 1);
	}

	function get_order_docs($sessionID, $ordn, $debug) { // FIXME USE PARAMATERS
		$sql = "SELECT * FROM orddocs WHERE sessionid = '$sessionID' AND orderno = '$ordn' AND itemnbr = '' ";
		if ($debug) {
			return $sql;
		} else {
			$results = wire('database')->query($sql);
			return $results;
		}
	}
/* =============================================================
	QUOTES FUNCTIONS
============================================================ */
	function hasaquotelocked($sessionID) {
		$sql = wire('database')->prepare("SELECT COUNT(*) FROM quotelock WHERE sessionid = :sessionID");
		$switching = array(':sessionID' => $sessionID); $withquotes = array(true);
		$sql->execute($switching);
		return $sql->fetchColumn();
	}

	function getlockedquotenbr($sessionID) {
		$sql = wire('database')->prepare("SELECT quotenbr FROM quotelock WHERE sessionid = :sessionID");
		$switching = array(':sessionID' => $sessionID); $withquotes = array(true);
		$sql->execute($switching);
		return $sql->fetchColumn();
	}

	function caneditquote($sessionID, $qnbr) {
		$sql = wire('database')->prepare("SELECT COUNT(*) FROM quotelock WHERE sessionid = :sessionID AND quotenbr = :qnbr");
		$switching = array(':sessionID' => $sessionID, ':qnbr' => $qnbr);
		$sql->execute($switching);
		return $sql->fetchColumn();
	}

	function count_customerquotes($sessionID, $custID, $debug) {
		$sql = wire('database')->prepare("SELECT COUNT(*) as count FROM quothed WHERE sessionid = :sessionID AND custid = :custID");
		$switching = array(':sessionID' => $sessionID, ':custID' => $custID); $withquotes = array(true,true);
		if ($debug) {
			returnsqlquery($sql->queryString, $switching, $withquotes);
		} else {
			$sql->execute($switching);
			return $sql->fetchColumn();
		}
	}

	function get_customerquotes($sessionID, $custID, $limit, $page, $useclass = false, $debug) {
		$limiting = returnlimitstatement($limit, $page);
		$sql = wire('database')->prepare("SELECT * FROM quothed WHERE sessionid = :sessionID AND custid = :custID $limiting");
		$switching = array(':sessionID' => $sessionID, ':custID' => $custID); $withquotes = array(true, true);
		if ($debug) {
			returnsqlquery($sql->queryString, $switching, $withquotes);
		} else {
			$sql->execute($switching);
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
		$sql = wire('database')->prepare($q->render());
		
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
		$sql = wire('database')->prepare($q->render());
		
		if ($debug) {
			return $q->generate_sqlquery($q->params);
		} else {
			$sql->execute($q->params);
			return $sql->fetchColumn();
		}
	}

	function get_quotehead($sessionID, $qnbr, $useclass = false, $debug) {
		$sql = wire('database')->prepare("SELECT * FROM quothed WHERE sessionid = :sessionID AND quotnbr = :qnbr");
		$switching = array(':sessionID' => $sessionID, ':qnbr' => $qnbr); $withquotes = array(true, true);
		if ($debug) {
			returnsqlquery($sql->queryString, $switching, $withquotes);
		} else {
			$sql->execute($switching);
			if ($useclass) {
				$sql->setFetchMode(PDO::FETCH_CLASS, 'Quote');
				return $sql->fetch();
			}
			return $sql->fetch(PDO::FETCH_ASSOC);
		}
	}

	function get_quotedetails($sessionID, $qnbr, $useclass, $debug) {
		$sql = wire('database')->prepare("SELECT * FROM quotdet WHERE sessionid = :sessionID AND quotenbr = :qnbr");
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
		$sql = wire('database')->prepare("SELECT * FROM quotdet WHERE sessionid = :sessionID AND quotenbr = :qnbr AND linenbr = :line");
		$switching = array(':sessionID' => $sessionID, ':qnbr' => $qnbr, ':line' => $line); $withquotes = array(true, true, true);
		if ($debug) {
			return	returnsqlquery($sql->queryString, $switching, $withquotes);
		} else {
			$sql->execute($switching);
			return $sql->fetch(PDO::FETCH_ASSOC);
		}
	}

	function getquotelinedetail($sessionID, $qnbr, $line, $debug) {
		$sql = wire('database')->prepare("SELECT * FROM quotdet WHERE sessionid = :sessionID AND quotenbr = :qnbr AND linenbr = :linenbr");
		$switching = array(':sessionID' => $sessionID, ':qnbr' => $qnbr, ':linenbr' => $line); $withquotes = array(true, true, true);
		if ($debug) {
			return returnsqlquery($sql->queryString, $switching, $withquotes);
		} else {
			$sql->execute($switching);
			return $sql->fetch(PDO::FETCH_ASSOC);
		}
	}

	function insertquoteline($sessionID, $qnbr, $linenbr, $debug) {
		$sql = wire('database')->prepare("INSERT INTO quotdet (sessionid, quotenbr, linenbr) VALUES (:sessionID, :qnbr, :linenbr)");
		$switching = array(':sessionID' => $sessionID, ':qnbr' => $qnbr, ':linenbr' => $linenbr); $withquotes = array(true, true, true);
		if ($debug) {
			return returnsqlquery($sql->queryString, $switching, $withquotes);
		} else {
			$sql->execute($switching);
			return array('sql' => returnsqlquery($sql->queryString, $switching, $withquotes), 'insertedid' => wire('database')->lastInsertId());
		}
	}

	function nextquotelinenbr($sessionID, $qnbr) {
		$sql = wire('database')->prepare("SELECT MAX(linenbr) FROM quotdet WHERE sessionid = :sessionID AND quotenbr = :qnbr ");
		$switching = array(':sessionID' => $sessionID, ':qnbr' => $qnbr); $withquotes = array(true, true);
		$sql->execute($switching);
		return intval($sql->fetchColumn()) + 1;
	}

	function edit_quotehead($sessionID, $qnbr, $quote, $debug) {
		$originalquote = get_quotehead(session_id(), $qnbr, false);
		$query = returnpreppedquery($originalquote, $quote);
		$sql = wire('database')->prepare("UPDATE quothed SET ".$query['setstatement']." WHERE sessionid = :sessionID AND quotnbr = :quotnbr");
		$query['switching'][':sessionID'] = $sessionID; $query['switching'][':quotnbr'] = $qnbr;
		$query['withquotes'][]= true; $query['withquotes'][] = true;

		if ($debug) {
			return	returnsqlquery($sql->queryString, $query['switching'], $query['withquotes']);
		} else {
			if ($query['changecount'] > 0) {
				$sql->execute($query['switching']);
			}
			return returnsqlquery($sql->queryString, $query['switching'], $query['withquotes']);
		}
	}

	function edit_quoteline($sessionID, $qnbr, $newdetails, $debug) {
		$originaldetail = getquotelinedetail(session_id(), $qnbr, $newdetails['linenbr'], false);
		$query = returnpreppedquery($originaldetail, $newdetails);
		$sql = wire('database')->prepare("UPDATE quotdet SET ".$query['setstatement']." WHERE sessionid = :sessionID AND quotenbr = :qnbr AND linenbr = :linenbr");
		$query['switching'][':sessionID'] = $sessionID; $query['switching'][':qnbr'] = $qnbr; $query['switching'][':linenbr'] = $newdetails['linenbr'];
		$query['withquotes'][] = true; $query['withquotes'][]= true; $query['withquotes'][] = true;
		if ($debug) {
			return	returnsqlquery($sql->queryString, $query['switching'], $query['withquotes']);
		} else {
			if ($query['changecount'] > 0) {
				$sql->execute($query['switching']);
			}
			return returnsqlquery($sql->queryString, $query['switching'], $query['withquotes']);
		}
	}

	function insert_orderlock($sessionID, $recnbr, $ordn, $userID, $date, $time, $debug) {
		$sql = wire('database')->prepare("INSERT INTO ordlock (sessionid, recno, date, time, orderno, userid) VALUES (:sessionID, :recnbr, :date, :time, :orderno, :userID)");
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
		$sql = wire('database')->prepare("DELETE FROM ordlock WHERE sessionid = :sessionID AND orderno = :ordn AND userid = :userID");
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
	NOTES FUNCTIONS
============================================================ */
	function can_write_sales_note($sessionID, $ordn) {
		$sql = wire('database')->prepare("SELECT status FROM ordrhed WHERE sessionid = :sessionID AND orderno = :ordn LIMIT 1 ");
		$switching = array(':sessionID' => $sessionID, ':ordn' => $ordn);
		$sql->execute($switching);
		$status = $sql->fetchColumn();
		if (strtolower($status) == "open" || strtolower($status) == "new") {
			return true;
		} else {
			return false;
		}
	}

	function get_dplusnotes($sessionID, $key1, $key2, $type, $debug) {
		$sql = wire('database')->prepare("SELECT * FROM qnote WHERE sessionid = :sessionID AND key1 = :key1 AND key2 = :key2 AND rectype = :type");
		$switching = array(':sessionID' => $sessionID, ':key1' => $key1, ':key2' => $key2, ':type' => $type);
		$withquotes = array(true, true, true, true);
		if ($debug) {
			return returnsqlquery($sql->queryString, $switching, $withquotes);
		} else {
			$sql->execute($switching);
			return $sql->fetchAll(PDO::FETCH_ASSOC);
		}
	}

	function getdplusnotecount($sessionID, $key1, $key2, $type, $debug) {
		$sql = wire('database')->prepare("SELECT COUNT(*) FROM qnote WHERE sessionid = :sessionID AND key1 = :key1 AND key2 = :key2 AND rectype = :type");
		$switching = array(':sessionID' => $sessionID, ':key1' => $key1, ':key2' => $key2, ':type' => $type);
		$withquotes = array(true, true, true, true);
		if ($debug) {
			return returnsqlquery($sql->queryString, $switching, $withquotes);
		} else {
			$sql->execute($switching);
			return $sql->fetchColumn();
		}
	}

	function hasdplusnote($sessionID, $key1, $key2, $type) {
		if (getdplusnotecount($sessionID, $key1, $key2, $type, false)) {
			return 'Y';
		} else {
			return 'N';
		}
	}

	function get_dplusnote($sessionID, $key1, $key2, $type, $recnbr, $debug) {
		$sql = wire('database')->prepare("SELECT * FROM qnote WHERE sessionid = :sessionID AND key1 = :key1 AND key2 = :key2 AND rectype = :type AND recno = :recnbr");
		$switching = array(':sessionID' => $sessionID, ':key1' => $key1, ':key2' => $key2, ':type' => $type, ':recnbr' => $recnbr);
		$withquotes = array(true, true, true, true, true);
		if ($debug) {
			return returnsqlquery($sql->queryString, $switching, $withquotes);
		} else {
			$sql->execute($switching);
			return $sql->fetch(PDO::FETCH_ASSOC);
		}
	}


	function edit_note($sessionID, $key1, $key2, $form1, $form2, $form3, $form4, $form5, $note, $recnbr, $date, $time, $width) {
		$sql = wire('database')->prepare("UPDATE qnote SET notefld = :note  WHERE sessionid = :sessionID AND key1 = :key1 AND key2 = :key2 AND form1 = :form1 AND form2 = :form2 AND form3 = :form3 AND form4 = :form4 AND form5 = :form5 AND recno = :recnbr");
		$switching = array(':note' => $note, ':form1' => $form1, ':form2' => $form2, ':form3' => $form3, ':form4' => $form4, ':form5' => $form5, ':sessionID' => $sessionID, ':key1' => $key1, ':key2' => $key2, ':recnbr' => $recnbr);
		$withquotes = array(true, true, true, true, true, true, true, true, true, true);
		$sql->execute($switching);
		return returnsqlquery($sql->queryString, $switching, $withquotes);
	}

	function deletenote($sessionID, $key1, $key2, $form1, $form2, $form3, $form4, $form5, $rectype, $recnbr) {
		$sql = wire('database')->prepare("DELETE FROM qnote WHERE sessionid = :sessionID AND key1 = :key1 AND key2 = :key2 AND form1 = :form1 AND form2 = :form2 AND form3 = :form3 AND form4 = :form4 AND form5 = :form5 AND recno = :recnbr AND rectype = :rectype");
		$switching = array(':sessionID' => $sessionID, ':key1' => $key1, ':key2' => $key2, ':form1' => $form1, ':form2' => $form2, ':form3' => $form3, ':form4' => $form4, ':form5' => $form5, ':recnbr' => $recnbr, ':rectype' => $rectype);
		$withquotes = array(true, true, true, true, true, true, true, true, true, true);
		$sql->execute($switching);
		return returnsqlquery($sql->queryString, $switching, $withquotes);
	}

	function insert_note($sessionID, $key1, $key2, $form1, $form2, $form3, $form4, $form5, $note, $rectype, $recno, $date, $time, $width) { // FIXME USE PARAMATERS
		$sql = "INSERT INTO qnote (sessionid, notefld, key1, key2, form1, form2, form3, form4, form5, rectype, recno, date, time, colwidth) VALUES ('$sessionID', '$note',
		'$key1', '$key2', '$form1', '$form2', '$form3', '$form4', '$form5', '$rectype', '$recno', '$date', '$time', '$width')";
		wire('database')->query($sql);
		return $sql;
	}

	function insertdplusnote($sessionID, $key1, $key2, $form1, $form2, $form3, $form4, $form5, $note, $rectype, $recno, $date, $time, $width) {// FIXME USE PARAMATERS
		$sql = wire('database')->prepare("INSERT INTO qnote (sessionid, notefld, key1, key2, form1, form2, form3, form4, form5, rectype, recno, date, time, colwidth) VALUES (:sessionID, :note,
		:key1, :key2, :form1, :form2, :form3, :form4, :form5, :rectype, :recno, :date, :time, :width)");
		$switching = array(':sessionID' => $sessionID, ':note' => $note, ':key1' => $key1, ':key2' => $key2, ':form1' => $form1, ':form2' => $form2, ':form3' => $form3, ':form4' => $form4, ':form5' => $form5, ':rectype' => $rectype, ':recno' => $recno, ':date' => $date, ':time' => $time, ':width' => $width);
		$withquotes = array(true, true, true, true, true, true, true, true, true, true, true, true, true, true);
		$sql->execute($switching);
		return array('sql' => returnsqlquery($sql->queryString, $switching, $withquotes), 'insertedid' => wire('database')->lastInsertId());
	}

	function get_next_note_recno($sessionID, $key1, $key2, $rectype) { // FIXME USE PARAMATERS
		$sql = "SELECT MAX(recno) as max FROM qnote WHERE sessionid = '$sessionID' AND key1 = '$key1' AND key2 = '$key2' AND rectype = '$rectype'";
		$res = wire('database')->query($sql);
		$result = $res->fetchColumn();
		$nextrecnbr =  intval($result) + 1;
		return $nextrecnbr;
	}


/* =============================================================
	PRODUCT FUNCTIONS
============================================================ */
	function get_item_search_results($sessionID, $limit = 10, $page = 1, $debug) {
		$limiting = returnlimitstatement($limit, $page);
		$sql = wire('database')->prepare("SELECT pricing.*, lastqty, lastsold, lastprice, ordn FROM pricing
JOIN custpricehistory ON custpricehistory.sessionid = pricing.sessionid AND pricing.itemid = custpricehistory.itemid WHERE pricing.sessionid = :sessionID " .$limiting);
		$switching = array(':sessionID' => $sessionID); $withquotes = array(true);
		$sql->execute($switching);
		if ($debug) {
			return returnsqlquery($sql->queryString, $switching, $withquotes);
		} else {
			return $sql->fetchAll(PDO::FETCH_ASSOC);
		}
	}

	function get_item_search_results_count($sessionID, $debug) {
		$sql = wire('database')->prepare("SELECT COUNT(*) FROM pricing WHERE sessionid = :sessionID ");
		$switching = array(':sessionID' => $sessionID); $withquotes = array(true);
		$sql->execute($switching);
		if ($debug) {
			return returnsqlquery($sql->queryString, $switching, $withquotes);
		} else {
			return $sql->fetchColumn();
		}
	}

	function getitemavailability($sessionID, $itemID, $debug) {
		$sql = wire('database')->prepare("SELECT * FROM whseavail WHERE sessionid = :sessionID AND itemid = :itemid");
		$switching = array(':sessionID' => $sessionID, ':itemid' => $itemID); $withquotes = array(true, true);
		$sql->execute($switching);
		if ($debug) {
			return returnsqlquery($sql->queryString, $switching, $withquotes);
		} else {
			return $sql->fetchAll(PDO::FETCH_ASSOC);
		}
	}

	/* =============================================================
		USER ACTION FUNCTIONS
	============================================================ */
	function get_useractions($user, $querylinks, $limit, $page, $debug) {
		$q = (new QueryBuilder())->table('useractions');
		
		if (wire('config')->cptechcustomer == 'stempf') {
			$q->generate_query($querylinks, "duedate-ASC", $limit, $page);
		} else {
			$q->generate_query($querylinks, false, $limit, $page);
		}
		
		$sql = wire('database')->prepare($q->render());
		
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
		$sql = wire('database')->prepare($q->render());
		
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
		$sql = wire('database')->prepare($q->render());
		
		if ($debug) {
			return $q->generate_sqlquery($q->params);
		} else {
			$sql->execute($q->params);
			$sql->setFetchMode(PDO::FETCH_CLASS, 'UserAction');
			return $sql->fetch();
		}
	}
	
	function edit_useraction(UserAction $updatedaction, $debug = false) {
		global $db; //FIX
		$originalaction = get_useraction($updatedaction->id); // (id, bool fetchclass, bool debug)
		$q = (new QueryBuilder())->table('useractions');
		$q->mode('update');
		$q->generate_setdifferencesquery($originalaction->toArray(), $updatedaction->toArray());
		$q->where('id', $updatedaction->id);
		$sql = wire('database')->prepare($q->render());
		
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
		$sql = wire('database')->prepare($q->render());
		
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
		$q->generate_setvaluesquery($action->toArray());
		$sql = wire('database')->prepare($q->render());
		
		if ($debug) {
			return $q->generate_sqlquery($q->params);
		} else {
			$sql->execute($q->params);
			return array('sql' => $q->generate_sqlquery($q->params), 'insertedid' => wire('database')->lastInsertId());
		}
	}

	function get_useractions_maxrec($loginID) {
		$sql = wire('database')->prepare("SELECT MAX(id) AS id FROM useractions WHERE createdby = :login");
		$switching = array(':login' => $loginID);
		$withquotes = array(true, true);
		$sql->execute($switching);
		return $sql->fetchColumn();
	}
/* =============================================================
	VENDOR FUNCTIONS
============================================================ */
	function getvendors($debug) {
		$sql = wire('database')->prepare("SELECT * FROM vendors WHERE shipfrom = ''");
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
		$sql = wire('database')->prepare($q->render());
		
		if ($debug) {
			return $q->generate_sqlquery($q->params);
		} else {
			$sql->execute($q->params);
			$sql->setFetchMode(PDO::FETCH_CLASS, 'Vendor');
			return $sql->fetch();
		}
	}

	function getvendorshipfroms($vendorID, $debug) {
		$sql = wire('database')->prepare("SELECT * FROM vendors WHERE vendid = :vendor AND shipfrom != ''");
		$switching = array(':vendor' => $vendorID); $withquotes = array(true);
		if ($debug) {
			return returnsqlquery($sql->queryString, $switching, $withquotes);
		} else {
			$sql->execute($switching);
			return $sql->fetchAll(PDO::FETCH_ASSOC);
		}
	}
	
	function search_vendorspaged($limit = 10, $page = 1, $keyword, $debug) {
		$SHARED_ACCOUNTS = wire('config')->sharedaccounts;
		$limiting = returnlimitstatement($limit, $page);
		$search = '%'.str_replace(' ', '%',$keyword).'%';
		$sql = wire('database')->prepare("SELECT * FROM vendors WHERE UCASE(CONCAT(vendid, ' ', shipfrom, ' ', name, ' ', address1, ' ', address2, ' ', address3, ' ', city, ' ', state, ' ', zip, ' ', country, ' ', phone, ' ', fax, ' ', email)) LIKE UCASE(:search) $limiting");
		$switching = array(':search' => $search); $withquotes = array(true);
		if ($debug) {
			return returnsqlquery($sql->queryString, $switching, $withquotes);
		} else {
			$sql->execute($switching);
			return $sql->fetchAll(PDO::FETCH_ASSOC);
		}
	}
	
	function count_searchvendors($keyword, $debug) {
		$SHARED_ACCOUNTS = wire('config')->sharedaccounts;
		$search = '%'.str_replace(' ', '%',$keyword).'%';
		$sql = wire('database')->prepare("SELECT COUNT(*) FROM vendors WHERE UCASE(CONCAT(vendid, ' ', shipfrom, ' ', name, ' ', address1, ' ', address2, ' ', address3, ' ', city, ' ', state, ' ', zip, ' ', country, ' ', phone, ' ', fax, ' ', email)) LIKE UCASE(:search)");
		$switching = array(':search' => $search); $withquotes = array(true);
		if ($debug) {
			return returnsqlquery($sql->queryString, $switching, $withquotes);
		} else {
			$sql->execute($switching);
			return $sql->fetchAll(PDO::FETCH_ASSOC);
		}
	}

	function getunitofmeasurements($debug) {
		$sql = wire('database')->prepare("SELECT * FROM unitofmeasure");
		if ($debug) {
			return $sql->queryString;
		} else {
			$sql->execute();
			return $sql->fetchAll(PDO::FETCH_ASSOC);
		}
	}

	function getitemgroups($debug) {
		$sql = wire('database')->prepare("SELECT * FROM itemgroup");
		if ($debug) {
			return $sql->queryString;
		} else {
			$sql->execute();
			return $sql->fetchAll(PDO::FETCH_ASSOC);
		}
	}
	
	function get_vendorname($vendorID) {
		$sql = wire('database')->prepare("SELECT name FROM vendors WHERE vendid = :vendorID LIMIT 1");
		$switching = array(':vendorID' => $vendorID);
		$sql->execute($switching);
		return $sql->fetchColumn();
	}

/* =============================================================
	CART FUNCTIONS
============================================================ */
	function getcartheadcount($sessionID, $debug) {
		$sql = wire('database')->prepare("SELECT COUNT(*) FROM carthed WHERE sessionid = :sessionID");
		$switching = array(':sessionID' => $sessionID); $withquotes = array(true);
		$sql->execute($switching);
		if ($debug) {
			return returnsqlquery($sql->queryString, $switching, $withquotes);
		} else {
			return $sql->fetchColumn();
		}
	}
	
	function get_custidfromcart($sessionID, $debug = false) {
		$q = (new QueryBuilder())->table('carthed');
		$q->field($q->expr('custid'));
		$q->where('sessionid', $sessionID);
		$sql = wire('database')->prepare($q->render());
		
		if ($debug) {
			return $q->generate_sqlquery($q->params);
		} else {
			$sql->execute($q->params);
			return $sql->fetchColumn();
		}
	}

	function getcarthead($sessionID, $debug) {
		$sql = wire('database')->prepare("SELECT * FROM carthed WHERE sessionid = :sessionID");
		$switching = array(':sessionID' => $sessionID); $withquotes = array(true);
		if ($debug) {
			return returnsqlquery($sql->queryString, $switching, $withquotes);
		} else {
			$sql->execute($switching);
			return $sql->fetch(PDO::FETCH_ASSOC);
		}
	}

	function editcarthead($sessionID, $carthead, $debug) {
		$orginalcarthead = getcarthead($sessionID, false);
		$query = returnpreppedquery($originalcarthead, $carthead);
		$sql = wire('database')->prepare("UPDATE carthed SET ".$query['setstatement']." WHERE sessionid = :sessionID");
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

	function getcart($sessionID, $debug) {
		$sql = wire('database')->prepare("SELECT * FROM cartdet WHERE sessionid = :sessionID");
		$switching = array(':sessionID' => $sessionID); $withquotes = array(true);
		$sql->execute($switching);
		if ($debug) {
			return returnsqlquery($sql->queryString, $switching, $withquotes);
		} else {
			return $sql->fetchAll(PDO::FETCH_ASSOC);
		}

	}

	function insertcarthead($sessionID, $custID, $shipID, $debug) {
		$sql = wire('database')->prepare("INSERT INTO carthed (sessionid, custid, shiptoid, date, time) VALUES (:sessionID, :custID, :shipID, :date, :time)");
		$switching = array(':sessionID' => $sessionID, ':custID' => $custID, ':shipID' => $shipID, ':date' => date('Ymd'), ':time' =>date('His')); $withquotes = array(true, true, true, false, false);

		if ($debug) {
			return returnsqlquery($sql->queryString, $switching, $withquotes);
		} else {
			$sql->execute($switching);
			return returnsqlquery($sql->queryString, $switching, $withquotes);
		}
	}

	function getcartline($sessionID, $linenbr, $debug) {
		$sql = wire('database')->prepare("SELECT * FROM cartdet WHERE sessionid = :sessionID AND linenbr = :linenbr");
		$switching = array(':sessionID' => $sessionID, ':linenbr' => $linenbr); $withquotes = array(true, true);
		$sql->execute($switching);
		if ($debug) {
			return returnsqlquery($sql->queryString, $switching, $withquotes);
		} else {
			return $sql->fetch(PDO::FETCH_ASSOC);
		}
	}

	function insertcartline($sessionID, $linenbr, $debug) {
		$sql = wire('database')->prepare("INSERT INTO cartdet (sessionid, linenbr) VALUES (:sessionID, :linenbr)");
		$switching = array(':sessionID' => $sessionID, ':linenbr' => $linenbr); $withquotes = array(true, true);
		if ($debug) {
			return returnsqlquery($sql->queryString, $switching, $withquotes);
		} else {
			$sql->execute($switching);
			return array('sql' => returnsqlquery($sql->queryString, $switching, $withquotes), 'insertedid' => wire('database')->lastInsertId());
		}
	}

	function getcartlinedetail($sessionID, $linenbr, $debug) {
		return getcartline($sessionID, $linenbr, $debug);
	}

	function edit_cartline($sessionID, $newdetails, $debug) {
		$originaldetail = getcartlinedetail($sessionID, $newdetails['linenbr'], false);
		$query = returnpreppedquery($originaldetail, $newdetails);
		$sql = wire('database')->prepare("UPDATE cartdet SET ".$query['setstatement']." WHERE sessionid = :sessionID AND linenbr = :linenbr");
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

	function nextcartlinenbr($sessionID) {
		$sql = wire('database')->prepare("SELECT MAX(linenbr) FROM cartdet WHERE sessionid = :sessionID");
		$switching = array(':sessionID' => $sessionID); $withquotes = array(true);
		$sql->execute($switching);
		return intval($sql->fetchColumn()) + 1;
	}

	function getcreatedordn($sessionID, $debug) {
		$sql = wire('database')->prepare("SELECT ordernbr FROM logperm WHERE sessionid = :sessionID");
		$switching = array(':sessionID' => $sessionID); $withquotes = array(true);
		$sql->execute($switching);
		if ($debug) {
			return returnsqlquery($sql->queryString, $switching, $withquotes);
		} else {
			return $sql->fetchColumn();
		}
	}



/* =============================================================
	EDIT ORDER FUNCTIONS
============================================================ */
	function can_editorder($sessionID, $ordn, $debug) {
		$sql = wire('database')->prepare("SELECT editord FROM ordrhed WHERE sessionid = :sessionID AND orderno = :ordn LIMIT 1");
		$switching = array(':sessionID' => $sessionID, ':ordn' => $ordn); $withquotes = array(true, true);
		if ($debug) {
			return returnsqlquery($sql->queryString, $switching, $withquotes);
		} else {
			$sql->execute($switching);
			$column = $sql->fetchColumn();
			if ($column != 'Y') { return false; } else { return true; }
		}
	}

	function get_orderhead($sessionID, $ordn, $useclass = false, $debug) {
		$sql = wire('database')->prepare("SELECT * FROM ordrhed WHERE sessionid = :sessionID AND orderno = :ordn AND type = 'O'");
		$switching = array(':sessionID' => $sessionID, ':ordn' => $ordn); $withquotes = array(true, true);
		if ($debug) {
			return returnsqlquery($sql->queryString, $switching, $withquotes);
		} else {
			$sql->execute($switching);
			if ($useclass) {
				$sql->setFetchMode(PDO::FETCH_CLASS, 'SalesOrder'); // CAN BE SalesOrder|SalesOrderEdit
				return $sql->fetch();
			}
			return $sql->fetch(PDO::FETCH_ASSOC);
		}
	}

	function getorderdetails($sessionID, $ordn, $debug) {
		$sql = wire('database')->prepare("SELECT * FROM ordrdet WHERE sessionid = :sessionID AND orderno = :ordn");
		$switching = array(':sessionID' => $sessionID, ':ordn' => $ordn); $withquotes = array(true, true);
		$sql->execute($switching);
		if ($debug) {
			return returnsqlquery($sql->queryString, $switching, $withquotes);
		} else {
			return $sql->fetchAll(PDO::FETCH_ASSOC);
		}
	}

	function getorderlinedetail($sessionID, $ordn, $linenumber, $debug) {
		$sql = wire('database')->prepare("SELECT * FROM ordrdet WHERE sessionid = :sessionID AND orderno = :ordn AND linenbr = :linenbr");
		$switching = array(':sessionID' => $sessionID, ':ordn' => $ordn, ':linenbr' => $linenumber); $withquotes = array(true, true, true);
		if ($debug) {
			return returnsqlquery($sql->queryString, $switching, $withquotes);
		} else {
			$sql->execute($switching);
			return $sql->fetch(PDO::FETCH_ASSOC);
		}
	}

	function getallorderdocs($sessionID, $ordn, $debug) {
		$sql = wire('database')->prepare("SELECT * FROM orddocs WHERE sessionid = :sessionID AND orderno = :ordn ORDER BY itemnbr ASC");
		$switching = array(':sessionID' => $sessionID, ':ordn' => $ordn); $withquotes = array(true, true);
		if ($debug) {
			return returnsqlquery($sql->queryString, $switching, $withquotes);
		} else {
			$sql->execute($switching);
			return $sql->fetchAll(PDO::FETCH_ASSOC);
		}
	}

	function getordertracking($sessionID, $ordn, $debug) {
		$sql = wire('database')->prepare("SELECT * FROM ordrtrk WHERE sessionid = :sessionID AND orderno = :ordn");
		$switching = array(':sessionID' => $sessionID, ':ordn' => $ordn); $withquotes = array(true, true);
		$sql->execute($switching);
		if ($debug) {
			return returnsqlquery($sql->queryString, $switching, $withquotes);
		} else {
			return $sql->fetchAll(PDO::FETCH_ASSOC);
		}
	}

	function edit_orderhead($sessionID, $ordn, $order, $debug) {
		$orginalorder = get_orderhead(session_id(), $ordn, false, false);
		$query = returnpreppedquery($orginalorder, $order);
		$sql = wire('database')->prepare("UPDATE ordrhed SET ".$query['setstatement']." WHERE sessionid = :sessionID AND orderno = :ordn");
		$query['switching'][':sessionID'] = $sessionID; $query['switching'][':ordn'] = $ordn;
		$query['withquotes'][] = true; $query['withquotes'][]= true;

		if ($debug) {
			return	returnsqlquery($sql->queryString, $query['switching'], $query['withquotes']);
		} else {
			if ($query['changecount'] > 0) {
				$sql->execute($query['switching']);
			}
			return returnsqlquery($sql->queryString, $query['switching'], $query['withquotes']);
		}
	}

	function edit_orderhead_credit($sessionID, $ordn, $paytype, $ccno, $xpd, $ccv) {
		$sql = wire('database')->prepare("UPDATE ordrhed SET paytype = :paytype, ccno = AES_ENCRYPT(:ccno, HEX(:sessionID)), xpdate = AES_ENCRYPT(:xpd, HEX(:sessionID)), ccvalidcode = AES_ENCRYPT(:ccv, HEX(:sessionID)) WHERE sessionid = :sessionID AND orderno = :ordn");
		$switching = array(':paytype' => $paytype, ':ccno' => $ccno, ':sessionID' => $sessionID, ':xpd' => $xpd, ':ccv' => $ccv, ':sessionID' => $sessionID, ':ordn' => $ordn);
		$withquotes = array(true ,true, true, true, true, true,true);
		$sql->execute($switching);
		return returnsqlquery($sql->queryString, $switching, $withquotes);
	}

	function edit_orderline($sessionID, $ordn, $newdetails, $debug) {
		$originaldetail = getorderlinedetail($sessionID, $ordn, $newdetails['linenbr'], false);
		$query = returnpreppedquery($originaldetail, $newdetails);
		$sql = wire('database')->prepare("UPDATE ordrdet SET ".$query['setstatement']." WHERE sessionid = :sessionID AND orderno = :ordn AND linenbr = :linenbr");
		$query['switching'][':sessionID'] = $sessionID; $query['switching'][':ordn'] = $ordn; $query['switching'][':linenbr'] = $newdetails['linenbr'];
		$query['withquotes'][] = true; $query['withquotes'][]= true; $query['withquotes'][] = true;
		if ($debug) {
			return	returnsqlquery($sql->queryString, $query['switching'], $query['withquotes']);
		} else {
			if ($query['changecount'] > 0) {
				$sql->execute($query['switching']);
			}
			return returnsqlquery($sql->queryString, $query['switching'], $query['withquotes']);
		}
	}

	function insertorderline($sessionID, $ordn, $linenbr, $debug) {
		$sql = wire('database')->prepare("INSERT INTO ordrdet (sessionid, orderno, linenbr) VALUES (:sessionID, :ordn, :linenbr)");
		$switching = array(':sessionID' => $sessionID, ':ordn' => $ordn, ':linenbr' => $linenbr); $withquotes = array(true, true, true);
		if ($debug) {
			return returnsqlquery($sql->queryString, $switching, $withquotes);
		} else {
			$sql->execute($switching);
			return array('sql' => returnsqlquery($sql->queryString, $switching, $withquotes), 'insertedid' => wire('database')->lastInsertId());
		}
	}

	function get_ordercreditcard($sessionID, $ordn, $debug) {
		$sql = wire('database')->prepare("SELECT sessionid, AES_DECRYPT(ccno , HEX(sessionid)) AS cardnumber, AES_DECRYPT(ccvalidcode , HEX(sessionid)) AS cardcode, AES_DECRYPT(xpdate, HEX(sessionid)) AS expiredate FROM ordrhed WHERE sessionid = :sessionID AND orderno = :ordn AND type = 'O'");
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
		$sql = wire('database')->prepare("SELECT code, via FROM shipvia WHERE sessionid = :sessionID");
		$switching = array(':sessionID' => $sessionID); $withquotes = array(true);
		$sql->execute($switching);
		return $sql->fetchAll(PDO::FETCH_ASSOC);
	}

/* =============================================================
	MISC ORDER FUNCTIONS
============================================================ */
	function getstates() {
		$sql = wire('database')->prepare("SELECT abbreviation as state, name FROM states");
		$sql->execute();
		return $sql->fetchAll(PDO::FETCH_ASSOC);
	}

	function getcountries() {
		$sql = wire('database')->prepare("SELECT * FROM countries");
		$sql->execute();
		return $sql->fetchAll(PDO::FETCH_ASSOC);
	}



/* =============================================================
	ITEM FUNCTIONS
============================================================ */
	function getiteminfo($sessionID, $itemID, $debug) {
		$sql = wire('database')->prepare("SELECT * FROM pricing WHERE sessionid = :sessionID AND itemid = :itemid LIMIT 1");
		$switching = array(':sessionID' => $sessionID, ':itemid' => $itemID); $withquotes = array(true, true);
		if ($debug) {
			return returnsqlquery($sql->queryString, $switching, $withquotes);
		} else {
			$sql->execute($switching);
			return $sql->fetch(PDO::FETCH_ASSOC);
		}
	}

	function getitemfromim($itemID, $debug) {
		$sql = wire('database')->prepare("SELECT * FROM pricing WHERE itemid = :itemid LIMIT 1");
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
	function search_itm($q, $onlyitemid, $custID, $limit, $page, $debug) {
		$limiting = returnlimitstatement($limit, $page);
		$search = '%'.str_replace(' ', '%', $q).'%';
		if (empty($custID)) {
			if ($onlyitemid) {
				$sql = wire('database')->prepare("SELECT * FROM itemsearch WHERE UCASE(itemid) LIKE UCASE(:search) AND origintype IN ('I', 'V') GROUP BY itemid $limiting");
				$switching = array(':search' => $search); $withquotes = array(true);
			} else {
				$sql = wire('database')->prepare("SELECT * FROM itemsearch WHERE UCASE(CONCAT(itemid, ' ', originid, ' ', desc1, ' ', desc2)) LIKE UCASE(:search) AND origintype IN ('I', 'V') GROUP BY itemid $limiting");
				$switching = array(':search' => $search); $withquotes = array(true);
			}
		} else {
			if ($onlyitemid) {
				$sql = wire('database')->prepare("SELECT * FROM itemsearch WHERE (originid = (:custID) AND UCASE(refitemid) LIKE UCASE(:search)) OR (UCASE(itemid) like UCASE(:search)) GROUP BY itemid $limiting ");
				$switching = array(':search' => $search, ':custID' => $custID); $withquotes = array(true, true);
			} else {
				$sql = wire('database')->prepare("SELECT * FROM itemsearch WHERE (UCASE(CONCAT(itemid, ' ', desc1, ' ', desc2)) LIKE UCASE(:search) AND origintype IN ('I', 'V')) OR (UCASE(CONCAT(itemid, ' ', refitemid, ' ', desc1, ' ', desc2)) LIKE UCASE(:search) AND originid = :custID) GROUP BY itemid $limiting");
				$switching = array(':search' => $search, ':custID' => $custID); $withquotes = array(true, true);
			}
		}
		if ($debug) {
			return returnsqlquery($sql->queryString, $switching, $withquotes);
		} else {
			$sql->execute($switching);
			return $sql->fetchAll(PDO::FETCH_ASSOC);
		}
	}

	function validateitemid($itemID, $custID, $debug) {
		if (empty($custID)) {
			$sql = wire('database')->prepare("SELECT COUNT(*) FROM itemsearch WHERE UCASE(itemid) = UCASE(:itemID) AND originid = 'I'");
			$switching = array(':itemID' => $itemID); $withquotes = array(true);
		} else {
			$sql = wire('database')->prepare("SELECT COUNT(*) FROM itemsearch WHERE (originid = (:custID) AND UCASE(refitemid) = UCASE(:itemID)) OR (UCASE(itemid) = UCASE(:itemID) AND origintype = 'I')");
			$switching = array(':itemID' => $itemID, ':custID' => $custID); $withquotes = array(true, true);
		}

		if ($debug) {
			return returnsqlquery($sql->queryString, $switching, $withquotes);
		} else {
			$sql->execute($switching);
			return $sql->fetchColumn();
		}
	}

	function search_itmcount($q, $onlyitemid, $custID, $debug) {
		$search = '%'.str_replace(' ', '%', $q).'%';
		if (empty($custID)) {
			if ($onlyitemid) {
				$sql = wire('database')->prepare("SELECT COUNT(*) FROM itemsearch WHERE UCASE(itemid) LIKE UCASE(:search) AND origintype IN ('I', 'V') GROUP BY itemid $limiting");
				$switching = array(':search' => $search); $withquotes = array(true);
			} else {
				$sql = wire('database')->prepare("SELECT COUNT(*) FROM itemsearch WHERE UCASE(CONCAT(itemid, ' ', originid, ' ', desc1, ' ', desc2)) LIKE UCASE(:search) AND origintype IN ('I', 'V')");
				$switching = array(':search' => $search); $withquotes = array(true);
			}
		} else {
			if ($onlyitemid) {
				$sql = wire('database')->prepare("SELECT COUNT(*) FROM itemsearch WHERE (originid = (:custID) AND UCASE(refitemid) LIKE UCASE(:search)) OR (UCASE(itemid) like UCASE(:search))");
				$switching = array(':search' => $search, ':custID' => $custID); $withquotes = array(true, true);
			} else {
				$sql = wire('database')->prepare("SELECT COUNT(*) FROM itemsearch WHERE (UCASE(CONCAT(itemid, ' ', originid, ' ', desc1, ' ', desc2)) LIKE UCASE(:search) AND origintype IN ('I', 'V')) OR (UCASE(CONCAT(itemid, ' ', refitemid, ' ', originid, ' ', desc1, ' ', desc2)) LIKE UCASE(:search) AND originid = :custID)");
				$switching = array(':search' => $search, ':custID' => $custID); $withquotes = array(true, true);
			}
		}
		if ($debug) {
			return returnsqlquery($sql->queryString, $switching, $withquotes);
		} else {
			$sql->execute($switching);
			return $sql->fetchColumn();
		}
	}

	function getitemdescription($itemID, $debug) {
		$sql = wire('database')->prepare("SELECT desc1 FROM itemsearch WHERE itemid = :itemid LIMIT 1");
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
			$sql = wire('database')->prepare("SELECT MAX(recno) + 1 FROM itemsearch WHERE itemid = :itemid");
		} else {
			$sql = wire('database')->prepare("SELECT MIN(recno) - 1 FROM itemsearch WHERE itemid = :itemid");
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
		$sql = wire('database')->prepare("SELECT itemid FROM itemsearch WHERE recno = :recno");
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
		$sql = wire('database')->prepare("SELECT data FROM tableformatter WHERE user = :user AND formattertype = :formatter LIMIT 1");
		$switching = array(':user' => $user, ':formatter' => $formatter); $withquotes = array(true, true);
		if ($debug) {
			return returnsqlquery($sql->queryString, $switching, $withquotes);
		} else {
			$sql->execute($switching);
			return $sql->fetchColumn();
		}
	}

	function addformatter($user, $formatter, $data, $debug) {
		$sql = wire('database')->prepare("INSERT INTO tableformatter (user, formattertype, data) VALUES (:user, :formatter, :data)");
		$switching = array(':user' => $user, ':formatter' => $formatter, ':data' => $data); $withquotes = array(true, true, true);
		if ($debug) {
			return returnsqlquery($sql->queryString, $switching, $withquotes);
		} else {
			$sql->execute($switching);
			return array('sql' => returnsqlquery($sql->queryString, $switching, $withquotes), 'insertedid' => wire('database')->lastInsertId());
		}
	}
	
	function does_tableformatterexist($userID, $formatter, $debug = false) {
		$q = (new QueryBuilder())->table('tableformatter');
		$q->field($q->expr('COUNT(*)'));
		$q->where('user', $userID);
		$q->where('formattertype', $formatter);
		$sql = wire('database')->prepare($q->render());
		
		if ($debug) {
			return $q->generate_sqlquery($q->params);
		} else {
			$sql->execute($q->params);
			return $sql->fetchColumn();
		}
	}

	function checkformatterifexists($user, $formatter, $debug) {
		$sql = wire('database')->prepare("SELECT COUNT(*) FROM tableformatter WHERE user = :user AND formattertype = :formatter");
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
		$sql = wire('database')->prepare($q->render());
		
		if ($debug) {
			return $q->generate_sqlquery($q->params);
		} else {
			$sql->execute($q->params);
			return $sql->fetchColumn();
		}
	}
	
	function getmaxtableformatterid($user, $formatter, $debug) {
		$sql = wire('database')->prepare("SELECT MAX(id) FROM tableformatter WHERE user = :user AND formattertype = :formatter");
		$switching = array(':user' => $user, ':formatter' => $formatter); $withquotes = array(true, true);
		if ($debug) {
			return returnsqlquery($sql->queryString, $switching, $withquotes);
		} else {
			$sql->execute($switching);
			return $sql->fetchColumn();
		}
	}

	function editformatter($user, $formatter, $data, $debug) {
		$sql = wire('database')->prepare("UPDATE tableformatter SET data = :data WHERE user = :user AND formattertype =  :formatter");
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
		$sql = wire('database')->prepare($q->render());
		
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
		$sql = wire('database')->prepare($q->render());
		
		if ($debug) {
			return $q->generate_sqlquery($q->params);
		} else {
			$sql->execute($q->params);
			return array('sql' => $q->generate_sqlquery($q->params), 'success' => wire('database')->lastInsertId() > 0 ? true : false, 'id' => wire('database')->lastInsertId(), 'querytype' => 'create');
		}
	}
	
	/* =============================================================
		USER CONFIGS FUNCTIONS
	============================================================ */
	function checkconfigifexists($user, $configuration, $debug) {
		$sql = wire('database')->prepare("SELECT COUNT(*) FROM userconfigs WHERE user = :user AND configtype = :config");
		$switching = array(':user' => $user, ':config' => $configuration); $withquotes = array(true, true);
		if ($debug) {
			return returnsqlquery($sql->queryString, $switching, $withquotes);
		} else {
			$sql->execute($switching);
			return $sql->fetchColumn();
		}
	}

	function getconfiguration($user, $configuration, $debug) {
		$sql = wire('database')->prepare("SELECT data FROM userconfigs WHERE user = :user AND configtype = :config LIMIT 1");
		$switching = array(':user' => $user, ':config' => $configuration); $withquotes = array(true, true);
		if ($debug) {
			return returnsqlquery($sql->queryString, $switching, $withquotes);
		} else {
			$sql->execute($switching);
			return $sql->fetchColumn();
		}
	}
?>
