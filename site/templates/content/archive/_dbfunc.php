<?php 

function get_custindex_paged($loginid, $limit = 10, $page = 1, $restrictions, $debug) {
		$SHARED_ACCOUNTS = wire('config')->sharedaccounts;
		$limiting = returnlimitstatement($limit, $page);

		if ($restrictions) {
			$sql = wire('database')->prepare("SELECT * FROM custindex WHERE splogin1 IN (:loginid, :shared) OR splogin2 = :loginid OR splogin3 = :loginid ". $limiting);
			$switching = array(':loginid' => $loginid, ':shared' => $SHARED_ACCOUNTS, ':loginid' => $loginid, ':loginid' => $loginid);
			$withquotes = array(true, true, true, true);
		} else {
			$sql = wire('database')->prepare("SELECT * FROM custindex " . $limiting);
			$switching = array();
			$withquotes = array();
		}

		if ($debug) {
			return returnsqlquery($sql->queryString, $switching, $withquotes);
		} else {
			$sql->execute($switching);
			return $sql->fetchAll(PDO::FETCH_ASSOC);
		}
	}

	function get_distinct_custindex_paged($loginid, $limit = 10, $page = 1, $restrictions, $debug) {
		$SHARED_ACCOUNTS = wire('config')->sharedaccounts;
		$limiting = returnlimitstatement($limit, $page);
		if ($restrictions) {
			$sql = wire('database')->prepare("SELECT * FROM view_distinct_customers WHERE custid IN (SELECT DISTINCT(custid) FROM view_distinct_cust_shiptos WHERE splogin1 IN (:loginid, :shared) OR splogin2 = :loginid OR splogin3 = :loginid) ". $limiting);
			$switching = array(':loginid' => $loginid, ':shared' => $SHARED_ACCOUNTS, ':loginid' => $loginid, ':loginid' => $loginid);
			$withquotes = array(true, true, true, true);
		} else {
			$sql = wire('database')->prepare("SELECT * FROM view_distinct_customers " . $limiting);
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

	function get_custindex_paged_keyword($loginid, $limit = 10, $page = 1, $keyword, $restrictions, $debug) {
		$SHARED_ACCOUNTS = wire('config')->sharedaccounts;
		$search = '%'.str_replace(' ', '%',$keyword).'%';
		$limiting = returnlimitstatement($limit, $page);
		if ($restrictions) {
			$sql = wire('database')->prepare("SELECT * FROM custindex WHERE UCASE(CONCAT(custid, ' ', name, ' ', shiptoid, ' ', addr1, ' ', ccity, ' ', cst, ' ', czip, ' ', cphone, ' ', contact, ' ', source, ' ', cphext)) LIKE UCASE(:search) AND recno IN (SELECT recno FROM custindex WHERE splogin1 IN (:loginid, :shared) OR splogin2 = :loginid OR splogin3 = :loginid) " . $limiting);
			$switching = array(':search' => $search, ':loginid' => $loginid, ':shared' => $SHARED_ACCOUNTS, ':loginid' => $loginid, ':loginid' => $loginid);
			$withquotes = array(true, true, true, true, true);
		} else {
			$sql = wire('database')->prepare("SELECT * FROM custindex WHERE UCASE(CONCAT(custid, ' ', name, ' ', shiptoid, ' ', addr1, ' ', ccity, ' ', cst, ' ', czip, ' ', cphone, ' ', contact, ' ', source, ' ', cphext)) LIKE UCASE(:search) " . $limiting);
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

	function get_custindex_count($loginid, $restrictions, $debug) {
		$SHARED_ACCOUNTS = wire('config')->sharedaccounts;
		if ($restrictions) {
			$sql = wire('database')->prepare("SELECT COUNT(*) FROM custindex WHERE splogin1 IN (:loginid, :shared) OR splogin2 = :loginid OR splogin3 = :loginid");
			$switching = array(':loginid' => $loginid, ':shared' => $SHARED_ACCOUNTS, ':loginid' => $loginid, ':loginid' => $loginid);
			$withquotes = array(true, true, true, true);
		} else {
			$sql = wire('database')->prepare("SELECT COUNT(*) FROM custindex");
			$switching = array(); $withquotes = array();
		}
		if ($debug) {
			return returnsqlquery($sql->queryString, $switching, $withquotes);
		} else {
			$sql->execute($switching);
			return $sql->fetchColumn();
		}

	}

	function get_distinct_custindex_count($loginid, $restrictions, $debug) {
		$SHARED_ACCOUNTS = wire('config')->sharedaccounts;
		if ($restrictions) {
			$sql = wire('database')->prepare("SELECT COUNT(*) FROM view_distinct_customers WHERE custid IN (SELECT DISTINCT(custid) FROM view_distinct_cust_shiptos WHERE splogin1 IN (:loginid, :shared) OR splogin2 = :loginid OR splogin3 = :loginid)");
			$switching = array(':loginid' => $loginid, ':shared' => $SHARED_ACCOUNTS, ':loginid' => $loginid, ':loginid' => $loginid);
			$withquotes = array(true, true, true, true);
		} else {
			$sql = wire('database')->prepare("SELECT COUNT(*) FROM view_distinct_customers");
			$switching = array(); $withquotes = array();
		}
		if ($debug) {
			return returnsqlquery($sql->queryString, $switching, $withquotes);
		} else {
			$sql->execute($switching);
			return $sql->fetchColumn();
		}

	}

	function get_custindex_keyword_count($loginid, $restrictions, $keyword, $debug) {
		$SHARED_ACCOUNTS = wire('config')->sharedaccounts;
		$search = '%'.str_replace(' ', '%', $keyword).'%';
		if ($restrictions) {
			$sql = wire('database')->prepare("SELECT COUNT(*) as count FROM custindex WHERE UCASE(CONCAT(custid, ' ', name, ' ', shiptoid, ' ', addr1, ' ', ccity, ' ', cst, ' ', czip, ' ', cphone, ' ', contact, ' ', source, ' ', cphext)) LIKE UCASE(:search) AND recno IN (SELECT recno FROM custindex WHERE splogin1 IN (:loginid, :shared) OR splogin2 = :loginid OR splogin3 = :loginid)");
			$switching = array(':search' => $search, ':loginid' => $loginid, ':shared' => $SHARED_ACCOUNTS, ':loginid' => $loginid, ':loginid' => $loginid);
			$withquotes = array(true, true, true, true, true);
		} else {
			$sql = wire('database')->prepare("SELECT COUNT(*) as count FROM custindex WHERE UCASE(CONCAT(custid, ' ', name, ' ', shiptoid, ' ', addr1, ' ', ccity, ' ', cst, ' ', czip, ' ', cphone, ' ', contact, ' ', source, ' ', cphext)) LIKE UCASE(:search)");
			$switching = array(':search' => $search); $withquotes = array(true);
		}
		if ($debug) {
			return returnsqlquery($sql->queryString, $switching, $withquotes);
		} else {
			$sql->execute($switching);
			return $sql->fetchColumn();
		}
	}

	function get_distinct_custindex_paged_keyword($loginid, $limit = 10, $page = 1, $keyword, $restrictions, $debug) {
		$SHARED_ACCOUNTS = wire('config')->sharedaccounts;
		$search = '%'.str_replace(' ', '%',$keyword).'%';
		$limiting = returnlimitstatement($limit, $page);
		if ($restrictions) {
			$sql = wire('database')->prepare("SELECT * FROM custindex WHERE UCASE(CONCAT(custid, ' ', name, ' ', shiptoid, ' ', addr1, ' ', ccity, ' ', cst, ' ', czip, ' ', cphone, ' ', contact, ' ', source, ' ', cphext)) LIKE UCASE(:search) AND (splogin1 IN (:loginid, :shared) OR splogin2 = :loginid OR splogin3 = :loginid) GROUP BY custid $limiting");
			$switching = array(':search' => $search, ':loginid' => $loginid, ':shared' => $SHARED_ACCOUNTS); $withquotes = array(true, true, true);
		} else {
			$sql = wire('database')->prepare("SELECT * FROM custindex WHERE UCASE(CONCAT(custid, ' ', name, ' ', shiptoid, ' ', addr1, ' ', ccity, ' ', cst, ' ', czip, ' ', cphone, ' ', contact, ' ', source, ' ', cphext)) LIKE UCASE(:search) GROUP BY custid $limiting");
			$switching = array(':search' => $search); $withquotes = array(true);
		}
		if ($debug) {
			return returnsqlquery($sql->queryString, $switching, $withquotes);
		} else {
			$sql->execute($switching);
			return $sql->fetchAll(PDO::FETCH_ASSOC);
		}
	}



	function get_user_task_schedule($user, $customerlink, $shiptolink, $contactlink, $limit, $page, $debug) {
		$limiting = returnlimitstatement($limit, $page);
		$querylinks = buildtaskquerylinks($user, $customerlink, $shiptolink, $contactlink, '', '', '', true, 'query');
		$sql = wire('database')->prepare("SELECT * FROM crmtaskscheduler WHERE $querylinks $limiting");
		$switching = buildtaskquerylinks($user, $customerlink, $shiptolink, $contactlink, '', '', '', true, 'switch');
		$withquotes = buildtaskquerylinks($user, $customerlink, $shiptolink, $contactlink, '', '', '', true, 'quotes');

		if ($debug) {
			return returnsqlquery($sql->queryString, $switching, $withquotes);
		} else {
			$sql->execute($switching);
			return $sql->fetchAll(PDO::FETCH_ASSOC);
		}
	}

	function createtaskschedule($date, $startdate, $loginid, $repeat, $interval, $fallson, $active, $desc, $customerlink, $shiptolink, $contactlink, $debug) {
		$sql = wire('database')->prepare("INSERT INTO crmtaskscheduler (datecreated, startdate, user, repeats, `interval`, fallson, active, description, customerlink, shiptolink, contactlink) VALUES (:datecreated, :startdate, :loginid, :repeat, :interval, :fallson, :active, :description, :customerlink, :shiptolink, :contactlink)");
		$switching = array(':datecreated' => $date,':startdate' => $startdate, ':loginid' => $loginid, ':repeat' => $repeat, ':interval' => $interval, ':fallson' => $fallson, ':active' => $active, ':description' => $desc, ':customerlink' => $customerlink, ':shiptolink' => $shiptolink,
		':contactlink' => $contactlink);
		$withquotes = array(true, true, true, true, true, true, true, true, true, true, true);
		if ($debug) {
			return returnsqlquery($sql->queryString, $switching, $withquotes);
		} else {
			$sql->execute($switching);
			return array('sql' => returnsqlquery($sql->queryString, $switching, $withquotes), 'insertedid' => wire('database')->lastInsertId());
		}

	} 

	function get_specfic_task_schedule($id, $debug) {
		$sql = wire('database')->prepare("SELECT * FROM crmtaskscheduler WHERE id = :id");
		$switching = array(':id' => $id); $withquotes = array(true);
		if ($debug) {
			return returnsqlquery($sql->queryString, $switching, $withquotes);
		} else {
			$sql->execute($switching);
			return $sql->fetch(PDO::FETCH_ASSOC);
		}
	}

	function get_month_difference($nextfallson, $currentdate, $debug) {
		$sql = wire('database')->prepare("SELECT ABS(PERIOD_DIFF(DATE_FORMAT(:current, '%Y%m'), DATE_FORMAT(:nextfallson, '%Y%m')))");
		$switching = array(':nextfallson' => $nextfallson, ':current' => $currentdate); $withquotes = array(true, false);
		$sql->execute($switching);
		if ($debug) {
			return returnsqlquery($sql->queryString, $switching, $withquotes);
		} else {
			return $sql->fetchColumn();
		}
	}

	function add_month_to_date($nextfallson, $difference, $debug) {
		$sql = wire('database')->prepare("SELECT DATE_ADD(:nextfallson, INTERVAL $difference MONTH)");
		$switching = array(':nextfallson' => $nextfallson); $withquotes = array(true);
		$sql->execute($switching);
		if ($debug) {
			return returnsqlquery($sql->queryString, $switching, $withquotes);
		} else {
			return $sql->fetchColumn();
		}
	}