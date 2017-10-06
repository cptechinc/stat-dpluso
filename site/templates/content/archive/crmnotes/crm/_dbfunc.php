<?php 
/* =============================================================
	CRM NOTES FUNCTIONS
============================================================ */
	function getlinkednotescount($linkarray, $debug) {
		$query = buildlinkquery($linkarray);
		$sql = wire('database')->prepare("SELECT COUNT(*) FROM crmnotes WHERE ".$query['querylinks']."");
		$switching = $query['switching'];
		$withquotes = $query['withquotes'];
		$sql->execute($switching);
		if ($debug) {
			return returnsqlquery($sql->queryString, $switching, $withquotes);
		} else {
			return $sql->fetchColumn();
		}
	}

	function buildlinkquery($linkarray) {
		$querylinks = ''; $switching = $withquotes = array();
		foreach ($linkarray as $link => $value) {
			if ($value) {
				$querylinks .= $link . ' = :'.$link . ' AND ';
				$switching[':'.$link] = $value;
				$withquotes[] = true;
			}
		}
		return array('querylinks' => rtrim($querylinks, ' AND '), 'switching' => $switching, 'withquotes' => $withquotes);
	}

	function getlinkednotes($linkarray, $limit, $page, $debug) {
		$limiting = returnlimitstatement($limit, $page);
		$query = buildlinkquery($linkarray);
		$sql = wire('database')->prepare("SELECT *FROM crmnotes WHERE ".$query['querylinks']. " ". $limiting);
		$switching = $query['switching'];
		$withquotes = $query['withquotes'];
		$sql->execute($switching);
		if ($debug) {
			return returnsqlquery($sql->queryString, $switching, $withquotes);
		} else {
			return $sql->fetchAll(PDO::FETCH_ASSOC);
		}
	}


	function loadcrmnote($noteid, $debug) {
		$sql = wire('database')->prepare("SELECT * FROM crmnotes WHERE id = :noteid");
		$switching = array(':noteid'=> $noteid);
		$withquotes = array(true);
		$sql->execute($switching);
		if ($debug) {
			return returnsqlquery($sql->queryString, $switching, $withquotes);
		} else {
			$sql->setFetchMode(PDO::FETCH_CLASS, 'Note');
			return $sql->fetch();
		}
	}

	function get_user_note_maxrec($loginid) {
		$sql = wire('database')->prepare("SELECT MAX(id) AS id FROM crmnotes WHERE writtenby = :login ");
		$switching = array(':login' => $loginid);
		$withquotes = array(true, true);
		$sql->execute($switching);
		return $sql->fetchColumn();
	}

	function writecrmnote($loginid, $date, $custid, $shipto, $contact, $ordn, $qnbr, $textbody) {
		$sql = wire('database')->prepare("INSERT INTO crmnotes (textbody,datecreated,writtenby,customerlink,shiptolink,contactlink,salesorderlink,quotelink) VALUES (:textbody, :date, :loginid, :custid, :shipto, :contact, :ordn, :qnbr)");
		$switching = array(':textbody' => $textbody, ':date' => $date, ':loginid' => $loginid, ':custid' => $custid, ':shipto' => $shipto, ':contact' => $contact, ':ordn' => $ordn,':qnbr' => $qnbr);
		$withquotes = array(true, true, true, true, true, true, true, true);
		$sql->execute($switching);
		return array('sql' => returnsqlquery($sql->queryString, $switching, $withquotes), 'insertedid' => wire('database')->lastInsertId());
	}
