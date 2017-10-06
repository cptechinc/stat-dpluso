<?php
/* =============================================================
    TASK FUNCTIONS
============================================================ */
    function loadtask($id, $debug) {
        $sql = wire('database')->prepare("SELECT * FROM crmtasks WHERE id = :taskid");
        $switching = array(':taskid' => $id); $withquotes = array(true);
        $sql->execute($switching);
        if ($debug) {
            return returnsqlquery($sql->queryString, $switching, $withquotes);
        } else {
            $sql->setFetchMode(PDO::FETCH_CLASS, 'Task');
            return $sql->fetch();
        }
    }

    function getparenttaskid($id, $debug) {
        $sql = wire('database')->prepare("SELECT tasklink FROM crmtasks WHERE id = :taskid");
        $switching = array(':taskid' => $id); $withquotes = array(true);
        $sql->execute($switching);
        if ($debug) {
            return returnsqlquery($sql->queryString, $switching, $withquotes);
        } else {
            return $sql->fetchColumn();
        }
    }

    function get_linked_task_count($user, $custid, $shipto, $contact, $ordn, $qnbr, $noteid, $status, $debug) {
        $table = returntaskstable($status);
        $query = buildtaskquerylinks($user, $custid, $shipto, $contact, $ordn, $qnbr, $noteid, false);
        $querylinks = $query['querylink'];
        $sql = wire('database')->prepare("SELECT COUNT(*) FROM $table WHERE $querylinks");
        $switching = $query['switching'];
        $withquotes = $query['withquotes'];

        if ($debug) {
            return returnsqlquery($sql->queryString, $switching, $withquotes);
        } else {
            $sql->execute($switching);
            return $sql->fetchColumn();
        }
    }

    function get_linked_tasks($user, $custid, $shipto, $contact, $ordn, $qnbr, $noteid, $status, $limit, $page, $debug) {
        $table = returntaskstable($status);
        $limiting = returnlimitstatement($limit, $page);
        $query = buildtaskquerylinks($user, $custid, $shipto, $contact, $ordn, $qnbr, $noteid, false);
        $querylinks = $query['querylink'];
        $sql = wire('database')->prepare("SELECT * FROM $table WHERE $querylinks $limiting");
        $switching = $query['switching'];
        $withquotes = $query['withquotes'];

        if ($debug) {
            return returnsqlquery($sql->queryString, $switching, $withquotes);
        } else {
            $sql->execute($switching);
            $sql->setFetchMode(PDO::FETCH_CLASS, 'Task');
            return $sql->fetchAll();
        }
    }


function buildtaskquerylinks($user, $custid, $shipto, $contact, $ordn, $qnbr, $noteid, $schedule) {
    if ($schedule) {
        $query = array('user' => ':user', 'customerlink' => ':custid', 'shiptolink' => ':shipto', 'contactlink' => ':contact');
    $switching = array(':user' => $user, ':custid'=> $custid, ':shipto' => $shipto, ':contact' => $contact);
    } else {
        $query = array('assignedto' => ':user', 'customerlink' => ':custid', 'shiptolink' => ':shipto', 'contactlink' => ':contact', 'salesorderlink' => ':ordn', 'quotelink' => ':quote',' notelink' => ':note');
    $switching = array(':user' => $user, ':custid'=> $custid, ':shipto' => $shipto, ':contact' => $contact, ':ordn' => $ordn, ':quote' => $qnbr, ':note' => $noteid);
    }
    $querylinks = '';
    $switchingarray = array();
    $withquotes = array();
    foreach ($query as $column => $val) {
        if ($switching[$val] != '') {
            $querylinks .= $column .' = '. $val." AND ";
            $switchingarray[$val] = $switching[$val];
            $withquotes[] = true;
        }
    }
    return array('querylink' => rtrim($querylinks, ' AND '), 'switching' => $switchingarray, 'withquotes' => $withquotes);
}

function updatetaskcompletion($taskid, $completedate, $updatedate, $completed) {
    $sql = wire('database')->prepare("UPDATE crmtasks SET completedate = :completedate, updatedate = :updatedate, completed = :completed WHERE id = :taskid");
    $switching = array(':completedate' => $completedate, ':updatedate' => $updatedate, ':completed' => $completed, ':taskid' => $taskid);
    $withquotes = array(true, true, true, true);
    $sql->execute($switching);
    $success = $sql->rowCount();
    if ($success) {
        return array("error" => false,  "sql" => returnsqlquery($sql->queryString, $switching, $withquotes));
    } else {
        return array("error" => true,  "sql" => returnsqlquery($sql->queryString, $switching, $withquotes));
    }
}

function writetask($loginid, $date, $custid, $shipto, $contact, $ordn, $qnbr, $noteid, $taskid, $textbody, $tasktype, $duedate, $assignedto) {
    $sql = wire('database')->prepare("INSERT INTO crmtasks (textbody,datewritten,writtenby,customerlink,shiptolink,contactlink,salesorderlink,quotelink,notelink, tasklink, tasktype, duedate, assignedto, updatedate) VALUES (:textbody, :date, :loginid, :custid, :shipto, :contact, :ordn, :qnbr, :noteid, :taskid, :tasktype, :duedate, :assignedto, :updatedate)");
    $switching = array(':textbody' => $textbody, ':date' => $date, ':loginid' => $loginid, ':custid' => $custid, ':shipto' => $shipto, ':contact' => $contact, ':ordn' => $ordn,':qnbr' => $qnbr, ':noteid' => $noteid, ':taskid' => $taskid, ':tasktype' => $tasktype, ':duedate' => $duedate, ':assignedto' => $assignedto, ':updatedate' => $date);
    $withquotes = array(true, true, true, true, true, true, true, true, true, true, true, true, true, true);
    $sql->execute($switching);
    return array('sql' => returnsqlquery($sql->queryString, $switching, $withquotes), 'insertedid' => wire('database')->lastInsertId());
}

function get_user_task_maxrec($loginid) {
    $sql = wire('database')->prepare("SELECT MAX(id) AS id FROM crmtasks WHERE writtenby = :login");
    $switching = array(':login' => $loginid);
    $withquotes = array(true, true);
    $sql->execute($switching);
    return $sql->fetchColumn();
}

function createtaskschedule($date, $startdate, $loginid, $repeatlogic, $desc, $customerlink, $shiptolink, $contactlink, $tasktype, $debug) {
    $active = 'Y';
    $sql = wire('database')->prepare("INSERT INTO taskscheduler (datecreated, startdate, user, active, description, repeatlogic, customerlink, shiptolink, contactlink, tasktype) VALUES (:datecreated, :startdate, :user, :active, :description, :repeatlogic, :custid, :shiptoid, :contactid, :taskstype)");
    $switching = array(':datecreated' => $date,':startdate' => $startdate, ':user' => $loginid, ':active' => $active, ':description' => $desc, ':repeatlogic' => $repeatlogic, ':custid' => $customerlink, ':shiptoid' => $shiptolink, ':contactid' => $contactlink, ':taskstype' => $tasktype);
    $withquotes = array(true, true, true, true, true, true, true, true, true, true);
    if ($debug) {
        return returnsqlquery($sql->queryString, $switching, $withquotes);
    } else {
        $sql->execute($switching);
        return array('sql' => returnsqlquery($sql->queryString, $switching, $withquotes), 'insertedid' => wire('database')->lastInsertId());
    }
}

function change_taskschedule_active($scheduleid, $activate, $debug) {
    if ($activate) { $active = 'Y'; } else { $active = 'N'; }
    $sql = wire('database')->prepare("UPDATE taskscheduler SET active = :active WHERE id = :id");
    $switching = array(':active' => $active, ':id' => $scheduleid);
    $withquotes = array(true, true);
    if ($debug) {
        return returnsqlquery($sql->queryString, $switching, $withquotes);
    } else {
        $sql->execute($switching);
        return returnsqlquery($sql->queryString, $switching, $withquotes);
    }
}

function get_user_taskscheduler_maxrec($loginid) {
    $sql = wire('database')->prepare("SELECT MAX(id) FROM taskscheduler WHERE user = :login");
    $switching = array(':login' => $loginid);
    $withquotes = array(true, true);
    $sql->execute($switching);
    return $sql->fetchColumn();
}

function get_user_scheduled_tasks($user, $customerlink, $shiptolink, $contactlink, $debug) {
    $query = buildtaskquerylinks($user, $custid, $shipto, $contact, $ordn, $qnbr, $noteid, false);
    $querylinks = $query['querylink'];
    $sql = wire('database')->prepare("SELECT * FROM taskscheduler WHERE $querylinks");
    $switching = $query['switching'];
    $withquotes = $query['withquotes'];
    if ($debug) {
        return returnsqlquery($sql->queryString, $switching, $withquotes);
    } else {
        $sql->execute($switching);
        return $sql->fetchAll(PDO::FETCH_ASSOC);
    }
}

function get_current_taskschedules() {
    $sql = wire('database')->prepare("SELECT * FROM taskscheduler WHERE active = 'Y' AND DATE_FORMAT(startdate, '%Y-%m-%e') >= CURDATE()");
    $sql->execute();
    return $sql->fetchAll(PDO::FETCH_ASSOC);
}

function scheduletask($user, $date, $duedate, $text, $customerlink, $shiptolink, $contactlink, $debug) {
    $sql = wire('database')->prepare("INSERT INTO crmtasks (datewritten, duedate, writtenby, assignedto, assignedby, textbody, customerlink, shiptolink, contactlink) VALUES (:datewritten, :duedate, 'task-scheduler', :user, 'task-scheduler', :text, :custid, :shiptoid, :contactid)");
    $switching = array(':datewritten' => $date, ':duedate', $duedate, ':user' => $user, ':text' => $text, ':custid' => $customerlink, ':shiptoid' => $shiptolink, ':contactid' => $contactlink);
    $withquotes = array(true, true, true, true, true, true, true);
    if ($debug) {
        return returnsqlquery($sql->queryString, $switching, $withquotes);
    } else {
        $sql->execute($switching);
        return array('sql' => returnsqlquery($sql->queryString, $switching, $withquotes), 'insertedid' => wire('database')->lastInsertId());
    }
}
